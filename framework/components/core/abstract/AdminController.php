<?php
namespace tree\core;

/**
 * No direct access to this file.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class      AdminController abstract class
 * @category  Core Components
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
abstract class AdminController extends Controller
{


    /**
     *  This is a STATIC variable. If the current controller has any views and it needs to be shown in Navigation BAR ( LEFT BAR )
     *  than in this variable we need to specify.
     *  In order to understand how the variable needs to be set here is an example:
     *
     * @type String MUST BE SET TO "normal"
     * @icon it supports fa / glyphicon icons. ONLY HAVE TO WRITE THE CSS CLASS HERE
     *         example: "fa fa-file" OR "glyphicon glyphicon-list"
     * @url YOU HAVE TO PROVIDE ONLY THE PATH AFTER THE site.com/ !! this is what you need to provide !!!
     *      full url is not needed!
     * @title text, which will be visible in the Navigation bar
     *
     * @items if there is no sub item, leave it blank BUT DO NOT DELETE IT!
     *
     * @subitems
     * @url as above
     * @title as above
     *
     *  public static $navbar = [
     * "type" => "normal",
     * "icon" => "fa / glyphicon",
     * "url" => "",
     * "title" => "",
     * "items" => [
     * [
     * "title" => "Item 2",
     * "url" => ""
     * ],
     * [
     * "title" => "Item 2",
     * "url" => ""
     * ]
     * ];
     *
     *  IMPORTANT:
     *  the LEFT NAVIGATION BAR SUPPORTS ONLY 2 LEVELS. THE MAIN LEVEL, AND ONE BEYOND
     *  NAVIGATION ITEM
     *          ITEM 1
     *          ITEM 2
     *
     * @return array
     */
    public function getAdminNavigationBar()
    {
        return array();
    }


    /**
     *
     * Static function which returns all the info widgets for a controller.
     * If the controller has no info Widget you have to do absolutely nothing.
     * Else please follow the structure of the info widget:
     *
     * @type string is static, is not needed to be changed.
     * @title first line of the widget
     * @subtitle second line of the widget
     * @color ONLY the name of the color. See the Documentation for available colors!
     * @icon fa / glyphicon icons are supported example: fa fa-icon | glyphicon glyphicon-icon
     *
     * public static function getInfoWidget() {
     * return [
     *          [
     *          "type" => "info-widget",
     *          "title" => "ITEM 1",
     *          "subtitle" => "",
     *          "color" => "",
     *          "icon" => "",
     *          "url"=>""
     *          ], [
     *          "type" => "info-widget",
     *          "title" => "ITEM 2",
     *          "subtitle" => "",
     *          "color" => "",
     *          "icon" => "",
     *          "url"=>""
     *          ]
     *      ];
     * }
     *
     *
     * @return array | null
     *
     */
    public static function getInfoWidget()
    {
        return null;
    }


    /**
     * Static function which returns all the necessary data for showing the settings field in the settings / controller section
     *
     * Basically it is a simple return function, which returns the name and keys.
     * @name string | mixed the name of the controller.
     * @keys a big array, contains all the necessary data. In order to know how a key looks like, we explain detailed every supported one below.
     *
     * public static function getSettings(){
     * return [
     *      "name" => "",
     *      "keys" => []
     *      ];
     * }
     *
     *  !!! LIST OF SUPPORTED KEYS !!!
     *
     *  1st - SELECTOR TYPE.
     *
     * @key string, this stores the key for this setting.
     * @values array, which stores the selector items. Each value has a title and a strict value for it.
     * @currentValue or default Value is required at rendering
     * @text string, this will be shown as a help for the user. here you can describe wjich setting
     *               will be changed with this selector
     * @type string, defines the form type. In this case is selector. PLEASE DO NOT CHANGE. IF you want to put another type of input
     *      check below the list, and find the best one for you!
     * [
     *     "key" => "KEY",
     *     "values" => [[
     *          "title" => "TITLE 1",
     *          "value" => "value 1"
     *          ], [
     *          "title" => "TITLE 2",
     *          "value" => "value 2"
     *          ]],
     *      "currentValue" => Settings::getInstance()->getSetting("KEY","VALUE"),
     *      "text" => "Enable / Disable the controller.",
     *      "type" => "selector"   !! DO NOT CHANGE THIS LINE !!
     * ]
     *
     *      !! EXAMPLE !!
     * IF THERE IS AN IMPLEMENTED getSettings method we STRONGLY RECOMMEND to add the following setting to it:
     *
     *  [
     *     "key" => " !! REPLACE THIS WITH THE CONTROLLER NAME BUT LEAVE THE "-enabled part"-enabled",
     *     "values" => [[
     *          "title" => "Enabled",
     *          "value" => "enabled"
     *          ], [
     *          "title" => "Disabled",
     *          "value" => "disabled"
     *          ]],
     *      "currentValue" => Settings::getInstance()->getSetting(" !!   THE KEY HERE AGAIN !!!    ","enabled"),
     *      "text" => "Enable / Disable the controller.",
     *      "type" => "selector"
     * ]
     *
     *
     *
     *
     * @return array | null
     */
    public static function getSettings()
    {
        return null;
    }


}