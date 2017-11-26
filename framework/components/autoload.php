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

require_once __DIR__ . '/email/Email.php';

/**
 * Required Classes only if we are in Admin Mode
 */
if (defined('ADMINPATH')):

    require_once __DIR__ . '/admin/Admin.php';


    require_once __DIR__ . '/filemanager/FileManager.php';

endif;


require_once __DIR__ . '/pluginmanager/PluginManager.php';

require_once __DIR__ . '/thememanager/ThemeManager.php';