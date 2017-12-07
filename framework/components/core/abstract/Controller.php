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
 * Class      Controller abstract class
 * @category  Core Components
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
abstract class Controller extends Object
{

    /**
     * This field holds the controllers model if there is any.
     * @var
     */
    public $model;

    /**
     * Default layout type. It may vary in type of action what the controller needs to do.
     * @var string
     */
    public $layout = 'main';

    /**
     * If the value is true, the default views/layouts/footer.php is used to render the basic scripts
     * Else the page or the view needs to contain all the necessary scripts!
     * @var bool
     */
    public $footer = true;


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
     * @var array
     */
    public static $navbar = [];

    /**
     * array that contains all the actions by the controller
     * It needs to be overwritten, if there is any action beside the index.
     * @var array
     */
    public $actions = array("index");

    /**
     * Main entry point, for the Controller. This method is need to be called to run a controller.
     */
    public function runAction()
    {
        ob_start();

        $action = App::app()->getAction();

        $actionFunction = 'action' . ucfirst($action);


        //if the action is listed in actions array, we trigger that function
        if (in_array($action, $this->actions)) {

            if (method_exists($this, $actionFunction)) {
                $this->$actionFunction();

            } else {

                self::error('The <strong>"' . $actionFunction . '"</strong> method in <strong>' . get_class($this) . '</strong> dose not exists! ');

            }
        } else {
            $this->actionIndex();
        }

        $content = ob_get_clean();

        $includefooter = $this->footer;

        $assets = App::app()->theme()->getAssets();


        if (in_array($this->layout, App::app()->theme()->getLayouts())) {

            // if layout file exists in app/views/layouts
            if (file_exists(App::app()->theme()->getDir() . '/view/layout/' . $this->layout . '.php')) {

                require_once App::app()->theme()->getDir() . '/view/layout/' . $this->layout . '.php';

            } else {
                self::error("Layout not found!");
            }
        } else {
            self::error("Layout not allowed / not found!");
        }

    }

    /**
     * Shows error page
     * @param $act string contains the error message
     */
    public static function error($act)
    {
        throw new ThemeLoaderException($act);
    }

    /**
     * Renders the view from views folder
     * First param is the page itself
     * Second param any necessary data to view file
     * @param $file string
     * @param $param mixed
     */
    public function renderView($file, $param = NULl)
    {
        if (file_exists(App::app()->theme()->getDir() . '/view/' . $file . '.php')) {
            require_once App::app()->theme()->getDir() . '/view/' . $file . '.php';
        } else {
            self::error('The <strong>"' . $file . '"</strong> view in ' . get_class($this) . ' dose not exists! ');
        }
    }

    /**
     * EMPTY actionIndex method, for prevent Method not found exception
     * THIS METHOD NEEDS TO BE IMPLEMENTED IN EVERY CONTROLLER BASE CLASS
     */
    public function actionIndex()
    {
        throw new UnImplementedMethodException("actionIndex() is not implementd");
    }

    /**
     * METHOD HANDLE search function. If there is anything returns in an array
     * if not the return is null
     *
     * IN ORDER TO USE THIS METHOD PROPERLY HERE IS AN EXAMPLE FOR IT:
     *
     * AT RETURN ARRAY THE FOLLOWING PARAMETERS ARE REQUIRED:
     *
     *  !NOTE! For the following THREE items we recommend to use the same params as in the navbar variable.
     *
     * @controller - contains the NAME of the controller, without the "Controller" tag.
     *              This will be shown as a header for the items
     *
     * @icon - fa / glyphicon icon, as a simbol for this category.
     *
     * @url - a simple word, or two which comes after the / in the url bar.
     *          Note. If you want that the search result to be shown in a specific view, we suggest to use the action paramater for this.
     *          Example:  controller/action?id=id
     *                    controller?id=id
     * @getter the NAME of the filed which comes after the "controller?" in the url
     *
     * @param  this IS THE MOST IMPORTANT FIELD. THIS HOLDS THE NAME of the field, which needs to be shown in results view from array.
     *        Example: array(){ "id" =>"12",
     *                          "title"=>"this is the title" }
     *        than the @name parameter in return it will BE: !! title !!
     *
     * @urlparam  the same as above, but this will be used at url generation
     *
     * @urlendparam if tehre is any neccessary getter to be send to view, here needts to be specified in the following form:
     *                  "key=val&key2=val2" ...
     *
     *
     *
     * Here is the full method:
     *
     * public static function getSearchResults($get){
     *
     * $model = new model();
     * $res = $model->getSearch($get);   // u get the result from a model, or form where you want.
     *
     * if (count($res) > 0) {  // if the result contains something we handel it.
     *   return  [
     *      "controller" => "Controller NAME",
     *      "icon"=>"glyphicon glyphicon-list",
     *      "url" => "categories",
     *      "getter" => CategoryModel::$_SHOW_NAME,
     *      "param" => "name",
     *      "urlparam" => "name",
     *      "urlendparam" =>"key=val",
     *      "results" => $res
     *      ];
     * }
     *      return null;    // there is nothing, return null
     * }
     *
     * @param $get string this contains the q parameter, the string which is SEARCHED.
     * @return  array | null
     */
    public static function getSearchResults($get)
    {
        return null;
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


    public static function isEnabled()
    {
        return "enabled";
    }

}