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
 * @since     1.0.0
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
        throw new UnImplementedMethodException("actionIndex() is not implemented");
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
     * $res = $model->getSearch($get);   // you get the result from a model, or form where you want.
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



    public static function isEnabled()
    {
        return "enabled";
    }

}