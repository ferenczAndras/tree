<?php
/**
 *            Admin components autoload
 * @category  Admin components
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH') || !defined('ADMINPATH')) {
    exit;
}

define("TABLE_USERS", "users");


if (defined('ADMINPATH')):

    require_once __DIR__ . '/activity/ActivityTracker.php';

    require_once __DIR__ . '/user/Login.php';

    require_once __DIR__ . '/user/Registration.php';

    if (version_compare(PHP_VERSION, '5.5.0', '<')) {
        // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
        // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
        require_once(__DIR__ . '/user/PasswordCompatibility.php');
    }

endif;