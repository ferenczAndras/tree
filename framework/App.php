<?php

namespace tree;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

use tree\core\Application;
use tree\core\Settings;
use tree\thememanager\ThemeLoader;

/**
 *            App; Main Application
 * @category  Tree Framework main file
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
class App extends Application
{


    public function __construct()
    {
        parent::__construct();
        $this->initMySqlDatabase();
        $this->initSettings(new Settings());

        $this->setApp($this);
    }


    public function run($type = null)
    {
        if ($type === Application::$APP_THEME) {
            ThemeLoader::load();
        } else if ($type === Application::$APP_ADMIN && defined('ADMINPATH')) {
            echo App::$APP_ADMIN;
            echo "NOT IMPLEMENTED";
            return;
        } else
            return;

    }

}