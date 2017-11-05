<?php

namespace tree\thememanager;


use Exception;
use tree\App as App;
use tree\core\Object;
use tree\core\Settings;
use tree\core\Theme;
use tree\core\ThemeLoaderException;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class      ThemeLoader class
 * @category  ThemeManager Components
 * @since     1.0.0
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
class ThemeLoader extends Object
{

    public static function load()
    {

        $themeIdentifier = App::app()->settings()->get(Theme::$CURRENT_THEME_SETTINGS_KEY);

        $themeIdentifier = Settings::getValue($themeIdentifier);

        try {
            $themeLoader = CONTENTPATH . "/themes/" . $themeIdentifier . "/autoload.php";

            if (!file_exists($themeLoader)) {
                throw new ThemeLoaderException ("Unable to load the $themeIdentifier theme. Please contact your web administrator.");
            } else {
                require_once $themeLoader;
            }

            $class = ucfirst($themeIdentifier) . 'Theme';

            $themeClass = "theme\\$themeIdentifier" . "\\$class";

            if (class_exists($themeClass)) {

                $theme = new $themeClass();

                $theme->run();

            } else {
                throw new ThemeLoaderException("Unable to initialize the $themeIdentifier theme main class. Please contact your web administrator.");
            }

        } catch (\Exception $e) {
            echo "Message : " . $e->getMessage();
            echo "Code : " . $e->getCode();
            die("Unable to load the current theme.\n");
        }

    }

}