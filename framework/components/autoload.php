<?php
/**
 * Class      Components autoload
 * @category  Tree Framework main file
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
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


require_once __DIR__ . '/helpers/Helpers.php';

require_once __DIR__ . '/database/Database.php';

require_once __DIR__ . '/core/Core.php';


//require_once './email/Email.php';
//
//require_once './admin/Admin.php';
//
//require_once './filemanager/FileManager.php';
//
//require_once './pluginmanager/PluginManager.php';
//
require_once __DIR__ . '/thememanager/ThemeManager.php';