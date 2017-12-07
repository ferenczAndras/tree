<?php

namespace tree\core;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Defines the SETTING TABLE NAME
 */
if (!defined("TREE_SETTINGS"))
    define("TREE_SETTINGS", "tree_settings");

use tree\App as App;

/**
 * Class      Settings class
 * @category  Core Components
 * @since     1.0.0
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
class Settings extends DatabaseModel
{

    /**
     * @var string
     */
    public static $SETTING_APP_NAME = "appName";
    public static $SETTING_APP_NAME_DEFAULT = "Tree Application";


    /**
     *  END
     */

    /**
     * @var array
     */
    private $values = array();

    protected static $setting_key = "setting_key";


    protected static $setting_value = "setting_value";


    public function __construct()
    {
        $this->table = TREE_SETTINGS;
    }

    /**
     * @param $key String to the key
     * @param null $defaultValue
     * @return array|null
     */
    public function get($key, $defaultValue = null)
    {

        App::app()->db()->where(self::$setting_key, $key);
        $setting = App::app()->db()->getOne($this->getTable());

        if ($setting) {
            return $setting;
        } else {
            return $defaultValue;
        }
    }

    /**
     * Function which inserts in to the settings table, a key.
     *
     * If there is a key already, it's going to be updated elsewhere creates a new record
     *
     * @param $key String
     * @param $value mixed
     */
    public function insert($key, $value)
    {

        App::app()->db()->where(self::$setting_key, $key);
        $setting = App::app()->db()->getOne($this->getTable());

        if ($setting) {
            $this->updateSetting($key, $value);
        } else {
            $this->insertNewSetting($key, $value);
        }
    }

    /**
     * Private function which updates the settings table, an existing key
     *
     * @param $key String
     * @param $value mixed
     */
    private function updateSetting($key, $value)
    {
        $data = [self::$setting_value => $value];

        App::app()->db()->where(self::$setting_key, $key);

        $ok = App::app()->db()->update($this->getTable(), $data);

        if ($ok) $this->addMessage("The $key was updated successfully!");
        else $this->addMessage("Something went wrong while we tried to update the $key key.");
    }

    /**
     * Private function which inserts a new value into the settings table
     *
     * @param $key String
     * @param $value mixed
     */
    private function insertNewSetting($key, $value)
    {

        $data = [
            self::$setting_key => $key,
            self::$setting_value => $value
        ];

        $ok = App::app()->db()->insert($this->getTable(), $data);

        if ($ok) $this->addMessage("The $key was created successfully!");
        else $this->addMessage(" Something went wrong while we tried to create the $key key.");
    }

    /**
     *
     * Loads and stores an array of values and its defaults
     *
     * $array = [];
     *
     * method to call: loadValues($array);
     *
     * @param array $valueArray
     */
    protected function initValues($valueArray = array())
    {
        foreach ($valueArray as $value) {
            $this->loadValue($value['identifier'], $value['default']);
        }
    }

    protected function loadValue($identifier, $default = null)
    {
        $value = $this->get($identifier, $default);
        $this->storeValue($identifier, $value);
    }


    protected function storeValue($identifier, $value)
    {

        if (is_array($value)) {
            if (isset($value[self::$setting_value])) {
                $value = $value[self::$setting_value];
            }
        }

        $this->values[$identifier] = $value;
    }

    protected function getStoredValue($identifier, $default = null)
    {
        if (isset($this->values[$identifier])) return $this->values[$identifier];
        return $default;
    }


    /**
     *
     * Parse the given parameter into a variable, which holds the setting value
     *
     * @param $setting mixed
     * @return mixed
     */
    public
    static function getValue($setting)
    {
        if ($setting != null && is_array($setting)) {
            if (isset($setting[self::$setting_value])) {
                return $setting[self::$setting_value];
            }
        }
        return null;
    }

    /**
     *
     * Parse the given parameter into a variable, which holds the setting value
     *
     * @param $setting mixed
     * @return mixed
     */
    public
    static function getKey($setting)
    {
        if ($setting != null && is_array($setting)) {
            if (isset($setting[self::$setting_key])) {
                return $setting[self::$setting_key];
            }
        }
        return null;
    }

}