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
 * Class      ThemeHook abstract class
 * @category  Core Components
 * @since     1.0.0
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
abstract class ThemeHook extends Object
{

    /**
     *
     */
    public function runPluginsAfterAdminTheme()
    {
//        App::app()->activePlugins()->runAllThePluginsBeforeThemeLoad();
    }

    /**
     *
     */
    public function runPluginsBeforeAdminTheme()
    {
        App::app()->activePlugins()->runAllThePluginsBeforeThemeLoad();
    }


    /**
     *
     */
    public function runPluginsAfterTheme()
    {
//        App::app()->activePlugins()->runAllThePluginsBeforeThemeLoad();
    }

    /**
     *  If the theme supports the plugins, than this method is called before the actual theme is constructed
     *
     * If there is a theme that dose not supports or dose not wants to suppport any plugin, this method needs to be overwritten
     *
     */
    public function runPluginsBeforeTheme()
    {
        App::app()->activePlugins()->runAllThePluginsBeforeThemeLoad();
    }


}