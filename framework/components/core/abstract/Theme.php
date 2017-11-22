<?php

namespace tree\core;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class      Theme abstract class
 * @category  Core Components
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
abstract class Theme extends Object
{
    /**
     * @var string used to get the current theme settings
     */
    public static $CURRENT_THEME_SETTINGS_KEY = "currentTheme";

    /**
     * Holds the current theme assets component
     * @var \tree\core\Assets;
     */
    protected $assets;

    /**
     * Holds the current theme base directory path
     * @var String
     */
    protected $dir;


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
     * Default run method for any theme main class
     */
    public function run()
    {
        throw new UnImplementedMethodException('Calling un implemented method: ' . get_class($this) . "::run()");
    }


}