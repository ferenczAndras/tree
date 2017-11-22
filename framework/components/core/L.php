<?php

namespace tree\core;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class      L object for language handling
 * @category  Core Components
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
class L extends Object
{

    protected static $instance;

    public function  __construct()
    {
        self::$instance = $this;
    }


    public static function t($message, $package = "tree")
    {
        return $message;
    }

    /**
     * @return L
     */
    public static function L()
    {
        if (self::$instance === null) {
            self::$instance = new L();
            return self::$instance;
        }
        return self::$instance;
    }

}