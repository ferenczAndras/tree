<?php

namespace tree\core;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}


/**
 * Class      Plugin abstract class
 * @category  Core Components
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
class Plugin extends Object
{
    /**
     * @var string used to get the active theme settings
     */
    public static $ACTIVE_PLUGINS_SETTINGS_KEY = "activePlugins";

    /**
     * Holds the current theme base directory path
     * @var String
     */
    public $dir;

    /**
     * Holds the current theme assets component
     * @var \tree\core\Assets;
     */
    public $assets;

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


}