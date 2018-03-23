<?php
/**
 *            Admin theme controller autoload
 * @category  Admin theme
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */

/**
 * No direct access to this admin file.
 */
if (!defined('ABSPATH') || !defined('ADMINPATH')) {
    exit;
}

require_once __DIR__ . '/NotFoundController.php';

require_once __DIR__ . '/SystemErrorController.php';

require_once __DIR__ . '/PasswordResetController.php';

/**
 * @param $class
 */
function adminControllerAutoLoader($class)
{
    if (strpos($class, "Controller")) {

        $class = str_replace('admin\controller', "", $class);
        $class = str_replace("\\core\\", "", $class);
        $class = str_replace("\\", "", $class);

        $file = __DIR__ . '/' . $class . '.php';

        if (file_exists($file)) {
            require_once $file;
            return;
        }

    }
}

spl_autoload_register('adminControllerAutoLoader');