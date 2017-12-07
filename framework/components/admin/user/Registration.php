<?php
namespace tree\components\admin;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

use PDO;
use PDOException;
use PHPMailer;
use tree\App;
use tree\core\L;
use tree\core\Object;
use tree\core\Settings;
use tree\email\generator\HtmlEmailBodyGenerator;

/**
 *            Registration Class
 * @category  Admin components
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      http://www.php-login.net
 * @link      https://github.com/panique/php-login-advanced/
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
class Registration extends Object
{

    /**
     * @var object $db_connection The database connection
     */
    private $db_connection = null;

    /**
     * @var bool success state of registration
     */
    public $registration_successful = false;

    /**
     * @var bool success state of verification
     */
    public $verification_successful = false;

    /**
     * @var array collection of error messages
     */
    public $errors = array();

    /**
     * @var array collection of success / neutral messages
     */
    public $messages = array();

    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$login = new Login();"
     */
    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        // if we have such a POST request, call the registerNewUser() method
        if (isset($_POST["register"])) {
            $this->registerNewUser($_POST['user_name'], $_POST['user_email'], $_POST['user_password_new'], $_POST['user_password_repeat'], $_POST["captcha"]);
            // if we have such a GET request, call the verifyNewUser() method
        } else if (isset($_GET["id"]) && isset($_GET["verification_code"])) {
            $this->verifyNewUser($_GET["id"], $_GET["verification_code"]);
        }
    }

    /**
     * Checks if database connection is opened and open it if not
     */
    private function databaseConnection()
    {
        // connection already opened
        if ($this->db_connection != null) {
            return true;
        } else {
            // create a database connection, using the constants from config/config.php
            try {
                // Generate a database connection, using the PDO connector
                // @see http://net.tutsplus.com/tutorials/php/why-you-should-be-using-phps-pdo-for-database-access/
                // Also important: We include the charset, as leaving it out seems to be a security issue:
                // @see http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers#Connecting_to_MySQL says:
                // "Adding the charset to the DSN is very important for security reasons,
                // most examples you'll see around leave it out. MAKE SURE TO INCLUDE THE CHARSET!"
                $this->db_connection = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
                return true;
                // If an error is catched, database connection failed
            } catch (PDOException $e) {
                $this->errors[] = L::t("Database connection problem.", "AdminRegistration");
                return false;
            }
        }
    }

    /**
     * handles the entire registration process. checks all error possibilities, and creates a new user in the database if
     * everything is fine
     * @param $user_name
     * @param $user_email
     * @param $user_password
     * @param $user_password_repeat
     * @param $captcha
     */
    private function registerNewUser($user_name, $user_email, $user_password, $user_password_repeat, $captcha)
    {
        // we just remove extra space on username and email
        $user_name = trim($user_name);
        $user_email = trim($user_email);

        // check provided data validity
        // TODO: check for "return true" case early, so put this first
        if (strtolower($captcha) != strtolower($_SESSION['captcha'])) {
            $this->errors[] = L::t("Captcha was wrong!", "AdminRegistration");
        } elseif (empty($user_name)) {
            $this->errors[] = L::t("Username field was empty!", "AdminRegistration");
        } elseif (empty($user_password) || empty($user_password_repeat)) {
            $this->errors[] = L::t("Password field was empty!", "AdminRegistration");
        } elseif ($user_password !== $user_password_repeat) {
            $this->errors[] = L::t("Password and password repeat are not the same!", "AdminRegistration");
        } elseif (strlen($user_password) < 6) {
            $this->errors[] = L::t("Password has a minimum length of 6 characters!", "AdminRegistration");
        } elseif (strlen($user_name) > 64 || strlen($user_name) < 2) {
            $this->errors[] = L::t("Username cannot be shorter than 2 or longer than 64 characters!", "AdminRegistration");
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $user_name)) {
            $this->errors[] = L::t("Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters!", "AdminRegistration");
        } elseif (empty($user_email)) {
            $this->errors[] = L::t("Email cannot be empty", "AdminRegistration");
        } elseif (strlen($user_email) > 64) {
            $this->errors[] = L::t("Email cannot be longer than 64 characters", "AdminRegistration");
        } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = L::t("Your email address is not in a valid email format", "AdminRegistration");

            // finally if all the above checks are ok
        } else if ($this->databaseConnection()) {
            // check if username or email already exists
            $query_check_user_name = $this->db_connection->prepare('SELECT user_name, user_email FROM users WHERE user_name=:user_name OR user_email=:user_email');
            $query_check_user_name->bindValue(':user_name', $user_name, PDO::PARAM_STR);
            $query_check_user_name->bindValue(':user_email', $user_email, PDO::PARAM_STR);
            $query_check_user_name->execute();
            $result = $query_check_user_name->fetchAll();

            // if username or/and email find in the database
            // TODO: this is really awful!
            if (count($result) > 0) {
                for ($i = 0; $i < count($result); $i++) {
                    $this->errors[] = ($result[$i]['user_name'] == $user_name) ? L::t("This username already exists", "AdminLogin") : L::t("This email address is already registered. Please use the \"I forgot my password\" page if you don't remember it.", "AdminLogin");
                }
            } else {
                // check if we have a constant HASH_COST_FACTOR defined (in config/hashing.php),
                // if so: put the value into $hash_cost_factor, if not, make $hash_cost_factor = null
                $hash_cost_factor = (defined('HASH_COST_FACTOR') ? HASH_COST_FACTOR : null);

                // crypt the user's password with the PHP 5.5's password_hash() function, results in a 60 character hash string
                // the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using PHP 5.3/5.4, by the password hashing
                // compatibility library. the third parameter looks a little bit shitty, but that's how those PHP 5.5 functions
                // want the parameter: as an array with, currently only used with 'cost' => XX.
                $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT, array('cost' => $hash_cost_factor));
                // generate random hash for email verification (40 char string)
                $user_activation_hash = sha1(uniqid(mt_rand(), true));

                // write new users data into database
                $query_new_user_insert = $this->db_connection->prepare('INSERT INTO users (user_name, user_password_hash, user_email, user_activation_hash, user_registration_ip, user_registration_datetime) VALUES(:user_name, :user_password_hash, :user_email, :user_activation_hash, :user_registration_ip, now())');
                $query_new_user_insert->bindValue(':user_name', $user_name, PDO::PARAM_STR);
                $query_new_user_insert->bindValue(':user_password_hash', $user_password_hash, PDO::PARAM_STR);
                $query_new_user_insert->bindValue(':user_email', $user_email, PDO::PARAM_STR);
                $query_new_user_insert->bindValue(':user_activation_hash', $user_activation_hash, PDO::PARAM_STR);
                $query_new_user_insert->bindValue(':user_registration_ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
                $query_new_user_insert->execute();

                // id of new user
                $user_id = $this->db_connection->lastInsertId();

                if ($query_new_user_insert) {
                    // send a verification email
                    if ($this->sendVerificationEmail($user_id, $user_email, $user_activation_hash)) {
                        // when mail has been send successfully
                        $this->messages[] = L::t("Your account has been created successfully and we have sent you an email. Please follow the instructions within that mail in order to activate your account", "AdminRegistration");
                        $this->registration_successful = true;
                    } else {
                        // delete this users account immediately, as we could not send a verification email
                        $query_delete_user = $this->db_connection->prepare('DELETE FROM users WHERE user_id=:user_id');
                        $query_delete_user->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                        $query_delete_user->execute();

                        $this->errors[] = L::t("Sorry, we could not send you an verification mail. Your account has NOT been created.", "AdminRegistration");
                    }
                } else {
                    $this->errors[] = L::t("Sorry, your registration failed. Please go back and try again.", "AdminRegistration");
                }
            }
        }
    }

    /**
     * sends an email to the provided email address
     * @param $user_id int
     * @param $user_email string
     * @param $user_activation_hash string
     * @return bool true if the email was sent successfully
     * @throws \phpmailerException if something is going wrong
     */
    public function sendVerificationEmail($user_id, $user_email, $user_activation_hash)
    {
        $mail = new PHPMailer();

        if (EMAIL_USE_SMTP) {
            // Set mailer to use SMTP
            $mail->IsSMTP();
            //useful for debugging, shows full SMTP errors
            //$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
            // Enable SMTP authentication
            $mail->SMTPAuth = EMAIL_SMTP_AUTH;
            // Enable encryption, usually SSL/TLS
            if (defined(EMAIL_SMTP_ENCRYPTION)) {
                $mail->SMTPSecure = EMAIL_SMTP_ENCRYPTION;
            }
            // Specify host server
            $mail->Host = EMAIL_SMTP_HOST;
            $mail->Username = EMAIL_SMTP_USERNAME;
            $mail->Password = EMAIL_SMTP_PASSWORD;
            $mail->Port = EMAIL_SMTP_PORT;
        } else {
            $mail->IsMail();
        }

        $mail->From = EMAIL_ADMIN_FROM;
        $mail->FromName = App::app()->settings()->get(Settings::$SETTING_APP_NAME, Settings::$SETTING_APP_NAME_DEFAULT);
        $mail->AddAddress($user_email);
        $mail->Subject = L::t("Account activation for " . App::app()->settings()->get(Settings::$SETTING_APP_NAME, Settings::$SETTING_APP_NAME_DEFAULT), "AdminRegistration");

        $link = App::getUrl(App::app()->adminFolder()) . '?id=' . urlencode($user_id) . '&verification_code=' . urlencode($user_activation_hash);

        $body = new HtmlEmailBodyGenerator();

        $mail->Body = $body->setType(HtmlEmailBodyGenerator::$TYPE_CLICK_HERE)
            ->setTitle(L::t("Account activation for " . App::app()->settings()->get(Settings::$SETTING_APP_NAME, Settings::$SETTING_APP_NAME_DEFAULT), "AdminRegistration"))
            ->setMessageBeginning(L::t("Hi there,<br/>A new account for " . App::app()->settings()->get(Settings::$SETTING_APP_NAME, Settings::$SETTING_APP_NAME_DEFAULT) . " was created with this e-mail address.</br>Please click on the button below to activate your account.<br/> Without this, you are not able to log in.", "AdminRegistration"))
            ->setMessageEnd(L::t("Best regards,<br/>" . App::app()->settings()->get(Settings::$SETTING_APP_NAME, Settings::$SETTING_APP_NAME_DEFAULT) . "<br/>" . App::getUrl(""), "AdminRegistration"))
            ->setLink($link)
            ->render();

        $mail->IsHTML(true);


        if (!$mail->Send()) {
            $this->errors[] = L::t("Verification Mail was not successfully sent! Error: ", "AdminRegistration") . $mail->ErrorInfo;
            return false;
        } else {
            return true;
        }
    }

    /**
     * checks the id/verification code combination and set the user's activation status to true (=1) in the database
     * @param $user_id
     * @param $user_activation_hash
     */
    public
    function verifyNewUser($user_id, $user_activation_hash)
    {
        // if database connection opened
        if ($this->databaseConnection()) {
            // try to update user with specified information
            $query_update_user = $this->db_connection->prepare('UPDATE users SET user_active = 1, user_activation_hash = NULL WHERE user_id = :user_id AND user_activation_hash = :user_activation_hash');
            $query_update_user->bindValue(':user_id', intval(trim($user_id)), PDO::PARAM_INT);
            $query_update_user->bindValue(':user_activation_hash', $user_activation_hash, PDO::PARAM_STR);
            $query_update_user->execute();

            if ($query_update_user->rowCount() > 0) {
                $this->verification_successful = true;
                $this->messages[] = L::t("Activation was successful! You can now log in!", "AdminRegistration");
            } else {
                $this->errors[] = L::t("Sorry, no such id/verification code combination here...", "AdminRegistration");
            }
        }
    }

}
