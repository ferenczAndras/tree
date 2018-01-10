<?php

namespace tree\core;

/**
 * No direct access to this file.
 */
use tree\App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class      Theme abstract class
 * @category  Core Components
 * @since     1.0.0
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
abstract class Theme extends ThemeHook
{

    /**
     * Current plugin version
     *
     * @var string
     */
    const VERSION = 'unknown';

    /**
     * @var string used for saving wording in databases
     */
    public static $WORDING = "TreeFramework";

    /**
     * @var string used to get the current theme settings
     */
    public static $CURRENT_THEME_SETTINGS_KEY = "currentTheme";

    /**
     * Holds the current theme assets component
     * @var \tree\core\Assets;
     */
    private $assets;

    /**
     * Holds the current theme base directory path
     * @var String
     */
    protected $dir;

    /**
     * @var bool if this variable is set to false, the theme is not going to run it's own controller but a controller;
     */
    protected $runControllerAfterPlugin;

    /**
     * List of layouts available.
     * @main contains all the basic elements for the site: navbar, header
     * @empty contains only the body html element tag
     * @ajax it is an empty file, where the @content variable is shown
     *
     * @var array
     */
    protected $layouts = array("main", "empty", "ajax");

    /**
     * Theme constructor.
     */
    public function __construct()
    {
        $this->updateRunControllerAfterPlugin(true);
    }

    public function updateRunControllerAfterPlugin($run = true)
    {
        $this->runControllerAfterPlugin = $run;
    }

    public function runThemeControllerAfterPlugin()
    {
        return $this->runControllerAfterPlugin;
    }

    /**
     * @param $dir string the current theme base directory path
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

    /**
     * @return array
     */
    public function getLayouts()
    {
        return $this->layouts;
    }


    /**
     * Main run method which handles all the plugin controllers and the theme controllers
     */
    public function run()
    {

        if (App::app()->type() === App::$APP_THEME) {
            $this->runPluginsBeforeTheme();
        }

        if ($this->runThemeControllerAfterPlugin()) {
            $this->runController();
        }

        if (App::app()->type() === App::$APP_THEME) {
            $this->runPluginsAfterTheme();
        }
    }


    /**
     * Default run method for any theme main class
     */
    public function runController()
    {
        throw new UnImplementedMethodException('Calling un implemented method: ' . get_class($this) . "::run()");
    }


}