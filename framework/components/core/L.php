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

    private $languageArray = array();

    public static function t($message, $package = "TreeFramework")
    {
        self::L()->addOneMessage($message, $package);
        return $message;
    }


    private function addOneMessage($message, $package)
    {

        if (isset($this->languageArray[$package])) {

            $messageArray = $this->languageArray[$package];

            if (!in_array($message, $messageArray)) {
                $this->languageArray[$package][] = $message;
            }

        } else {
            $this->languageArray[$package] = [$message];
        }
    }

    public function writeMessages()
    {
        var_dump($this->languageArray);
    }


    protected static $instance;

    public function  __construct()
    {
        self::$instance = $this;
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