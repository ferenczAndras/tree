<?php

namespace tree\core;

use tree\App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class      AdminTheme abstract class
 * @category  Core Components
 * @since     1.0.0
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
class AdminTheme extends Theme
{

    /**
     * @return bool
     * TODO: set back to normal when all the debug is done
     */
    public function isUserLoggedIn()
    {
        return App::app()->login()->isUserLoggedIn();
    }

    public function redirectToLoginPage()
    {
        throw new UnImplementedMethodException("redirectToLoginPage() not implemented");
    }

    /**
     * Main run method which handles all the plugin controllers and the theme controllers
     */
    public function run()
    {

        if ($this->isUserLoggedIn()) {

            if (App::app()->type() === App::$APP_THEME) {
                $this->runPluginsBeforeTheme();
            }

            if (App::app()->type() === App::$APP_ADMIN) {
                $this->runPluginsBeforeAdminTheme();
            }

            if ($this->runThemeControllerAfterPlugin()) {
                $this->runTheme();
            }

            if (App::app()->type() === App::$APP_THEME) {
                $this->runPluginsAfterTheme();
            }

            if (App::app()->type() === App::$APP_ADMIN) {
                $this->runPluginsAfterAdminTheme();
            }
        } else {
            $this->redirectToLoginPage();
        }
    }

}