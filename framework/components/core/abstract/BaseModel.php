<?php
namespace tree\core;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

use Exception;
use tree\App;

/**
 * Class      BaseModel abstract class
 * @category  Core Components
 * @since     1.0.0
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
abstract class BaseModel extends DatabaseModel
{

    protected $secretKey = "CHANGE_THIS_TO_A_SECRET_KEY";

    protected $secretKeyIdentifier = "sec";

    protected static $_instance = null;


    /**
     * @return BaseModel | mixed
     */
    public static function getInstance()
    {
        $className = self::className();

        if (self::$_instance == null) {
            self::$_instance = new $className();
        }
        return self::$_instance;
    }

    /**
     * @return string the secret key for FORM post and get actions in a base64 form
     */
    public function getSecretKey()
    {
        return base64_encode($this->secretKey);
    }

    /**
     * @param $secKey string Initialize the model secret key for FORM post and Get actions
     */
    public function initSecretKey($secKey)
    {
        $this->secretKey = $secKey;
    }

    /**
     * @param $key string the key we try to validate
     * @return bool the answear
     */
    public function validateSecretKey($key)
    {
        return base64_decode($key) == $this->secretKey;
    }

    /**
     * @return bool Checks if the current form post contains the correct security key
     */
    protected function handleSecurityFormPost()
    {
        if (isset($_POST[$this->secretKeyIdentifier]) == false) {
            return false;
        }
        return $this->validateSecretKey($_POST[$this->secretKeyIdentifier]);
    }

    /**
     * @return bool Checks if the current form get contains the correct security key
     */
    protected function handleSecurityFormGet()
    {
        if (isset($_GET[$this->secretKeyIdentifier]) == false) {
            return false;
        }
        return $this->validateSecretKey($_GET[$this->secretKeyIdentifier]);
    }


    protected function saveActivity($what = array(), $type = "GENERAL")
    {
        if (App::app()->type() === App::$APP_ADMIN && App::app()->activityTracker() != NULL):
            if (isset($what['message']) && isset($what['getter']) && isset($what['objectId']) && isset($what['url'])) :

                switch ($type):

                    case "NEW":
                    case "new":
                        App::app()->activityTracker()->newEditActivity($what['message'], $what['getter'], $what['objectId'], $what['url']);
                        break;
                    default:

                        break;
                endswitch;
            endif;
        endif;

    }


    /**
     * TODO: Get rid of this method
     * Generates a string from the name array of the model
     * @param $titles
     * @return string
     */
    public function generateName($titles)
    {
        $title = "";
        foreach ($titles as $t):
            if (strlen($t) > 0)
                $title = $t . " - " . $title;
        endforeach;
        return $title;
    }






    /*
     *
     * TODO: Reconfigure the hole validation system.
     *
     */


    /*
     * Rules for validation
     * This method must be overwritten!
     */
    public function rules()
    {
        return array();
    }


    public function validateArray($array, $rules = null)
    {
        if ($rules == null) {
            $rules = $this->rules();
        }
        if (is_array($rules)) {
            foreach ($rules as $ruleitem) {
                $attributes = explode(',', $ruleitem['attributes']);
                foreach ($attributes as $attribute) {
                    $attribute = trim($attribute);

                    switch ($ruleitem['rule']) {
                        case 'required':
                            if (!isset($array[$attribute]) || empty($array[$attribute])) {
                                $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (001)";
                            }
                            break;
                        case 'email':
                            if (!filter_var($array[$attribute], FILTER_VALIDATE_EMAIL)) {
                                $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (002)";
                            }
                            break;
                        case 'regexp':
                            if (preg_match($ruleitem['expression'], $array[$attribute])) {
                                $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (003)";
                            }
                            break;
                        case 'matches':
                            $field = $ruleitem['field'];
                            if ($array[$attribute] != $array[$field]) {
                                $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (004)";
                            }
                            break;

                        case 'length':
                            $min = isset($ruleitem['min']) ? $ruleitem['min'] : null;
                            $max = isset($ruleitem['max']) ? $ruleitem['max'] : null;
                            if ($min && $max) {
                                if (strlen($array[$attribute]) < $min || strlen($array[$attribute]) > $max) {
                                    $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (005)";
                                }
                            } else if ($min) {
                                if (strlen($array[$attribute]) < $min) {
                                    $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (005)";
                                }
                            } else if ($max) {
                                if (strlen($array[$attribute]) > $max) {
                                    $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (005)";
                                }
                            }
                            break;

                        default:
                            throw new \Exception('Rule "' . $ruleitem['rule'] . '" is not declared in code.');
                            break;
                    }
                }
            }
        }
        return empty($this->errors);
    }


    /**
     * Validates model instance against the definition in rules()
     * @return Boolean
     * @throws Exception
     */
    public function validate()
    {
        $rules = $this->rules();
        if (is_array($rules)) {
            foreach ($rules as $ruleitem) {
                $attributes = explode(',', $ruleitem['attributes']);
                foreach ($attributes as $attribute) {
                    $attribute = trim($attribute);

                    switch ($ruleitem['rule']) {
                        case 'required':
                            if (!isset($this->$attribute) || empty($this->$attribute)) {
                                $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (001)";
                            }
                            break;
                        case 'email':
                            if (!filter_var($this->$attribute, FILTER_VALIDATE_EMAIL)) {
                                $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (002)";
                            }
                            break;
                        case 'regexp':
                            if (preg_match($ruleitem['expression'], $this->$attribute)) {
                                $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (003)";
                            }
                            break;
                        case 'matches':
                            $field = $ruleitem['field'];
                            if ($this->$attribute != $this->$field) {
                                $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (004)";
                            }
                            break;

                        case 'length':
                            $min = isset($ruleitem['min']) ? $ruleitem['min'] : null;
                            $max = isset($ruleitem['max']) ? $ruleitem['max'] : null;
                            if ($min && $max) {
                                if (strlen($this->$attribute) < $min || strlen($this->$attribute) > $max) {
                                    $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (005)";
                                }
                            } else if ($min) {
                                if (strlen($this->$attribute) < $min) {
                                    $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (005)";
                                }
                            } else if ($max) {
                                if (strlen($this->$attribute) > $max) {
                                    $this->errors[] = isset($ruleitem['error']) ? $ruleitem['error'] : "Something went wrong. (005)";
                                }
                            }
                            break;

                        default:
                            throw new \Exception('Rule "' . $ruleitem['rule'] . '" is not declared in code.');
                            break;
                    }
                }
            }
        }
        return empty($this->errors);
    }
}