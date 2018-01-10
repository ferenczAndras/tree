<?php

namespace tree\core;

/**
 * No direct access to this file.
 */

if (!defined('ABSPATH')) {
    exit;
}

use tree\App as App;

/**
 * Class      Plugin abstract class
 * @category  Core Components
 * @since     1.0.0
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
class Plugin extends Object
{

    /**
     * Current plugin version
     *
     * @var string
     */
    const VERSION = 'unknown';

    /**
     * @var null | string this variable holds the current plugin identifier string. It must be overwritten
     */
    public static $IDENTIFIER = "";

    /**
     * @var string used to get the active theme settings
     */
    public static $ACTIVE_PLUGINS_SETTINGS_KEY = "activePlugins";

    /**
     * Holds the current theme base directory path
     * @var String
     */
    protected $dir;

    /**
     * Holds the current theme assets component
     * @var \tree\core\Assets;
     */
    public $assets;

    /**
     * @var Plugin | mixed
     */
    protected static $_instance = null;

    /**
     * @param $instance Plugin
     */
    public static function initPlugin($instance)
    {
        self::$_instance = $instance;
    }

    /**
     * @return Plugin | mixed
     */
    private static function getInstance()
    {
        $className = self::className();

        if (self::$_instance == null) {
            self::$_instance = new $className();
        }
        return self::$_instance;
    }

    /**
     * @return mixed|Plugin
     */
    public static function plugin()
    {
        return self::getInstance();
    }

    /**
     * @param $dir string currernt plugin directory
     */
    public function setDir($dir)
    {
        $this->dir = $dir;
    }

    /**
     * Returns the current theme base directory path
     * @return String
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * Set the current Asset variable for this theme
     * @param $assets \tree\core\Assets
     */
    public function setAssets($assets)
    {
        $this->assets = $assets;
    }

    /**
     * @return Assets
     */
    public function getAssets()
    {
        return $this->assets;
    }


    public function runBefore()
    {
        if (App::app()->type() === App::$APP_THEME)
            $this->runBeforeTheme();
        else if (App::app()->type() === App::$APP_ADMIN)
            $this->runAdmin();
    }

    /**
     *   This method is the one, where the plugin can do it's own magic.
     * It can run it's own Controller, or initialize all the necessary stufs for the theme
     *
     */
    protected function runBeforeTheme()
    {
        throw new UnImplementedMethodException("runBeforeTheme() must be implemented in the plugin");
    }

    /**
     *
     */
    protected function runAfterTheme()
    {
        throw new UnImplementedMethodException("runAfterTheme() must be implemented in the plugin");
    }

    /**
     *
     */
    protected function runAdmin()
    {
        throw new UnImplementedMethodException("runAdmin() must be implemented in the plugin");
    }

    /**
     * @return array
     */
    public static function adminNavigationBar()
    {
        return array();
    }

    /**
     * @return array [
     *
     *  "name" =>"plugin_name",
     *  "description" =>"",
     *  "identifier" => "identifier",
     *  "adminUrl" => "base_admin_url_page",
     *  "developer" => "developer name",
     *  "developerIdentifier" =>"developerIdentifier",
     *  "pluginUrl" => "plugin_url",
     *  "version" => "",
     *  "tags" => ["tag1","tag2"]
     *
     * ]
     *
     */
    public static function adminConfig()
    {
        return array();
    }


}