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
     *
     */
    public function runPluginsBeforeTheme()
    {
        App::app()->activePlugins()->runAllThePluginsBeforeThemeLoad();
    }


}