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
    private $pluginIdentifierss;

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

    public function addPluginIdentifier($identity)
    {
        $this->pluginIdentifierss[] = $identity;
    }

    /**
     * @return mixed
     */
    public function getPluginsIdentifierArray()
    {
        return $this->pluginIdentifierss;
    }

    /**
     * @return ActivePlugins
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new ActivePlugins();
            return self::$instance;
        }
        return self::$instance;
    }

}