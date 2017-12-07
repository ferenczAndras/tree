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
     * ActivePlugins constructor.
     */
    public function __construct()
    {
        self::$instance = $this;
    }

    public function load()
    {
        $activePluginsIdentifier = App::app()->settings()->get(Plugin::$ACTIVE_PLUGINS_SETTINGS_KEY);

        $activePluginsIdentifier = Settings::getValue($activePluginsIdentifier);

        $activePluginsIdentifier = json_decode($activePluginsIdentifier);

        foreach ($activePluginsIdentifier as $a) {
            $this->addPluginIdentifier($a);
        }

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
            $pluginLoader = CONTENTPATH . "/plugins/" . $pluginIdentifier . "/autoload.php";

            if (!file_exists($pluginLoader)) {
                throw new PluginLoaderException ("Unable to load the $pluginIdentifier plugin.");
            } else {
                require_once $pluginLoader;
            }

            $class = ucfirst($pluginIdentifier);

            $pluginClass = "plugin\\$pluginIdentifier" . "\\$class";

            if (class_exists($pluginClass)) {

                $theme = new $pluginClass();

                $theme->runBefore();

            } else {
                throw new PluginLoaderException("Unable to initialize the $pluginIdentifier plugin main class. ");
            }

        } catch (\Exception $e) {
            throw new PluginLoaderException("Unable to load the current plugin. E: " . $e->getMessage());
        }
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