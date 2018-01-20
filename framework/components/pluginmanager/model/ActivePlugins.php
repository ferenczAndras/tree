<?php
namespace tree\pluginmanager;


use tree\App as App;
use tree\core\Object;
use tree\core\Plugin;
use tree\core\PluginLoaderException;
use tree\core\Settings;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class      ActivePlugins class
 * @category  PluginManager Components
 * @since     1.0.0
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
class ActivePlugins extends Object
{

    /**
     * @var ActivePlugins
     */
    protected static $instance;

    /**
     * @var array list of the active plugins
     */
    private $pluginIdentifiers;

    /**
     * @var array list of the active plugin classes
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

    public function load($require = false)
    {
        $activePluginsIdentifier = App::app()->settings()->get(Plugin::$ACTIVE_PLUGINS_SETTINGS_KEY);

        $activePluginsIdentifier = Settings::getValue($activePluginsIdentifier);

        $activePluginsIdentifier = json_decode($activePluginsIdentifier);

        foreach ($activePluginsIdentifier as $identifier) {
            $this->addPluginIdentifier($identifier);

            if ($require == true)
                $this->loadAndRequireOncePlugin($identifier);

            $class = ucfirst($identifier);
            $this->addPluginClass("plugin\\$identifier" . "\\$class");

        }

        $this->loaded = true;

    }

    public function runAllThePluginsBeforeThemeLoad()
    {

        foreach ($this->getPluginsIdentifierArray() as $pluginIdentifier):
            $this->runPluginBeforeThemeLoad($pluginIdentifier);
        endforeach;

    }

    public function runPluginBeforeThemeLoad($pluginIdentifier)
    {
        try {

            $pluginClass = $this->loadAndRequireOncePlugin($pluginIdentifier);

            if (class_exists($pluginClass)) {

                $plugin = new $pluginClass();

                $plugin->runBefore();

            } else {
                throw new PluginLoaderException("Unable to initialize the $pluginIdentifier plugin main class. ");
            }

        } catch (\Exception $e) {
            throw new PluginLoaderException("Unable to load the current plugin. E: " . $e->getMessage());
        }
    }

    /**
     * @param $pluginIdentifier string the plugin which we want to load into system
     * @return string the plugin main class with full namespace
     * @throws PluginLoaderException
     */
    public function loadAndRequireOncePlugin($pluginIdentifier)
    {
        $pluginLoader = ABSPATH . DIRECTORY_SEPARATOR . CONTENT . DIRECTORY_SEPARATOR . PLUGINS . DIRECTORY_SEPARATOR . $pluginIdentifier . DIRECTORY_SEPARATOR . "autoload.php";

        if (!file_exists($pluginLoader)) {
            throw new PluginLoaderException ("Unable to load the $pluginLoader file.");
        } else {
            require_once $pluginLoader;
        }

        $class = ucfirst($pluginIdentifier);

        $pluginClass = "plugin\\$pluginIdentifier" . "\\$class";

        return $pluginClass;
    }

    /**
     * @param $identity String
     */
    public function addPluginIdentifier($identity)
    {
        $this->pluginIdentifiers[] = $identity;
    }

    public function addPluginClass($class)
    {
        $this->pluginClasses[] = $class;
    }

    public function getPluginClasses($require = false)
    {
        if ($this->loaded === false) {
            $this->load($require);
        }
        return $this->pluginClasses;
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
     * @return ActivePlugins
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new ActivePlugins();
        }
        return self::$instance;
    }

}