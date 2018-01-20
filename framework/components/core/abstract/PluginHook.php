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
 * Class      PluginHook abstract class
 * @category  Core Components
 * @since     1.0.0
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
abstract class PluginHook extends Object
{

    /**
     *
     */
    public function runBefore()
    {
        if (App::app()->type() === App::$APP_THEME)
            $this->runBeforeTheme();
        else if (App::app()->type() === App::$APP_ADMIN)
            $this->runBeforeAdmin();
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
    protected function runBeforeAdmin()
    {
        throw new UnImplementedMethodException("runAdmin() must be implemented in the plugin");
    }

    /**
     *
     */
    protected function runAfterAdmin()
    {
        throw new UnImplementedMethodException("runAdmin() must be implemented in the plugin");
    }

}