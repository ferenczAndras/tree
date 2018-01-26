<?php

namespace tree\pluginmanager;


use tree\App as App;
use tree\core\Object;
use tree\core\Plugin;
use tree\core\Settings;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class      InstalledPlugins class
 * @category  PluginManager Components
 * @since     1.0.0
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
class InstalledPlugins extends Object
{
    /**
     * @var InstalledPlugins
     */
    private static $instance;

    /**
     * @var array list of the installed plugins
     */
    private $pluginIdentifiers;

    /**
     * @var array list of the installed plugin classes
     */
    private $pluginClasses;

    /**
     * @var boolean holds if the plugins were loaded or not
     */
    private $loaded = false;

    /**
     * ActivePlugins constructor.
     * @param bool $load do we want to load the identifiers at construct
     */
    public function __construct($load = false)
    {
        if ($load) {
            $this->load();
        }
        self::$instance = $this;
    }


    private function load()
    {

        $activePluginsIdentifier = App::app()->settings()->get(Plugin::$INSTALLED_PLUGINS_SETTINGS_KEY);

        $activePluginsIdentifier = Settings::getValue($activePluginsIdentifier);

        $activePluginsIdentifier = json_decode($activePluginsIdentifier);

        foreach ($activePluginsIdentifier as $identifier) {
            $this->addPluginIdentifier($identifier);


            $class = ucfirst($identifier);
            $this->addPluginClass("plugin\\$identifier" . "\\$class");
        }

        $this->loaded = true;

    }


    public function addPluginClass($class)
    {
        $this->pluginClasses[] = $class;
    }

    public function getPluginClasses()
    {
        if ($this->loaded === false) {
            $this->load();
        }
        return $this->pluginClasses;
    }


    /**
     * @param $identity String
     */
    public function addPluginIdentifier($identity)
    {
        $this->pluginIdentifiers[] = $identity;
    }

    /**
     * @return array
     */
    public function getPluginsIdentifierArray()
    {
        if ($this->loaded === false) {
            $this->load();
        }
        return $this->pluginIdentifiers;
    }

    /**
     * @return InstalledPlugins
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new InstalledPlugins();
        }
        return self::$instance;
    }

}