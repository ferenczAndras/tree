<?php
/**
 * Class      config.php
 * @category  core
 * @since     1.0.0
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2018 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}


/**
 * This fields are used for developments purposes. If your website is live, we suggest to delete from config !
 */

define("TREE_DEVELOPMENT", true);

define("TREE_DEBUG", true);

/**
 * End of the development fields
 */

/****
 * ------------------------------
 * AUTO GENERATED SOURCE FROM THIS POINT PLEASE DO NOT MODIFY
 * ------------------------------
 ****/

define("APP_INSTALLED", true);

if (!defined("ADMIN_FOLDER"))
    define("ADMIN_FOLDER", "admin");

require_once __DIR__ . '/db.php';


/****
 * ------------------------------
 ****/

if (defined("ADMINPATH")):


    /**
     * Configuration for: Email server credentials
     *
     * Here you can define how you want to send emails.
     * If you have successfully set up a mail server on your linux server and you know
     * what you do, then you can skip this section. Otherwise please set EMAIL_USE_SMTP to true
     * and fill in your SMTP provider account data.
     *
     * An example setup for using gmail.com [Google Mail] as email sending service,
     * works perfectly in August 2015. Change the "xxx" to your needs.
     * Please note that there are several issues with gmail, like gmail will block your server
     * for "spam" reasons or you'll have a daily sending limit. See the readme.md for more info.
     *
     * define("EMAIL_USE_SMTP", true);
     * define("EMAIL_SMTP_HOST", "ssl://smtp.gmail.com");
     * define("EMAIL_SMTP_AUTH", true);
     * define("EMAIL_SMTP_USERNAME", "xxxxxxxxxx@gmail.com");
     * define("EMAIL_SMTP_PASSWORD", "xxxxxxxxxxxxxxxxxxxx");
     * define("EMAIL_SMTP_PORT", 465);
     * define("EMAIL_SMTP_ENCRYPTION", "ssl");
     *
     * It's really recommended to use SMTP!
     *
     */
    define("EMAIL_USE_SMTP", false);
    define("EMAIL_SMTP_HOST", "yourhost");
    define("EMAIL_SMTP_AUTH", true);
    define("EMAIL_SMTP_USERNAME", "yourusername");
    define("EMAIL_SMTP_PASSWORD", "yourpassword");
    define("EMAIL_SMTP_PORT", 465);
    define("EMAIL_SMTP_ENCRYPTION", "ssl");


    define("EMAIL_ADMIN_FROM", "no-reply@example.com");


endif;