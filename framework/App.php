<?php

namespace tree;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

use tree\core\Application;
use tree\core\IllegalAppTypeException;
use tree\core\L;
use tree\core\Settings;
use tree\pluginmanager\ActivePlugins;
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

    public function __construct($type = null)
    {
        parent::__construct();
        $this->initAppType($type);
        $this->initMySqlDatabase();
        $this->initSettings(new Settings());
        $this->initLanguage(new L());
        $this->initActivePlugins(new ActivePlugins());
        $this->initApp($this);
    }


    public function run()
    {
        $this->activePlugins()->load();

        if ($this->type() === Application::$APP_THEME) {
            ThemeLoader::load();
        } else if ($this->type() === Application::$APP_ADMIN && defined('ADMINPATH')) {
            echo App::$APP_ADMIN;

            throw new IllegalAppTypeException();

        } else
            throw new IllegalAppTypeException();

    }

}