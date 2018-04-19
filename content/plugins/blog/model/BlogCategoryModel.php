<?php
namespace plugin\blog\model;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

use tree\App as App;
use tree\core\BaseModel;


/**
 * Class BlogCategoryModel
 * @category  Admin panel model
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-present Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 */
class BlogCategoryModel extends BaseModel
{

    public static $_EDIT_ACTIVITY_GETTER = "editname";
    public static $_CREATE_ACTIVITY_GETTER = "createname";
    public static $_SHOW_NAME = "showname";
    public static $_DELETE_ACTIVITY_GETTER = "deletename";


    function __construct()
    {
        $this->setTableName("tree_plugin_blog_category");
        $this->initSecretKey("CHAdsfsdfsfds)SGh_fgdfdgSECRET_KEY");

        $this->handlePost();

        self::$_instance = $this;
    }

    public static function getCategories()
    {
        return App::app()->db()->get("tree_plugin_blog_category");
    }

    public function getInfoMessage()
    {
        $user = filter_input(INPUT_GET, 'u');

        $time = filter_input(INPUT_GET, 't');

        if (filter_input(INPUT_GET, self::$_EDIT_ACTIVITY_GETTER)) {
            return [
                "title" => $user . " edited a category: " . filter_input(INPUT_GET, self::$_EDIT_ACTIVITY_GETTER),
                "time" => $time
            ];
        } else if (filter_input(INPUT_GET, self::$_CREATE_ACTIVITY_GETTER)) {
            return [
                "title" => $user . " created a category: " . filter_input(INPUT_GET, self::$_CREATE_ACTIVITY_GETTER),
                "time" => $time
            ];
        } else if (filter_input(INPUT_GET, self::$_DELETE_ACTIVITY_GETTER)) {
            return [
                "title" => $user . " deleted a category: " . filter_input(INPUT_GET, self::$_DELETE_ACTIVITY_GETTER),
                "time" => $time
            ];
        } else if (filter_input(INPUT_GET, self::$_SHOW_NAME)) {
            return [
                "title" => "Category: " . filter_input(INPUT_GET, self::$_SHOW_NAME),
                "time" => $time
            ];
        }
        return null;
    }

    private function handlePOST()
    {
        if (isset($_POST['newcategory']) && isset($_POST['category']) && isset($_POST['value']) && $this->handleSecurityFormPost()) {

            $this->addNewCategory($_POST['category'], $_POST['value']);

        } else if (isset($_POST['editcategory']) && isset($_POST['id']) && isset($_POST['category']) && isset($_POST['value']) && $this->handleSecurityFormPost()) {

            $this->editCategory(base64_decode($_POST['id']), $_POST['category'], $_POST['value']);
        }
    }


    private function editCategory($id, $name, $value)
    {
        if (count($name) > 0) {

            $ok = false;

            foreach ($name as $n) {
                if (strlen($n) > 0) $ok = true;
            }

            if ($ok == false) {
                $this->errors[] = "The name field can not be empty. At least in one of the languages you have to provide.";
                return;
            }

            App::app()->db()->where("value", $value);
            App::app()->db()->where("id", $id, "!=");
            $c = App::app()->db()->getOne($this->getTable());

            if ($c == false) {


                App::app()->db()->where('id', $id);
                $n = $this->generateName($name);
                $data = array("name" => json_encode($name), "value" => $value);

                $ok = App::app()->db()->update($this->getTable(), $data);
                if ($ok) {
                    $this->messages[] = "The " . $value . " category was updated successfully!";
                    $this->clearPostData();
//                    App::app()->get("activitytracker")->newEditActivity(App::app()->get("login")->getUsername(), "edited the " . $n . " blog category", self::$_EDIT_ACTIVITY_GETTER, $n, "blogcategories");
                } else {
                    $this->errors[] = "Something went wrong updating the " . $value . " category!";
                }

            } else {
                $this->errors[] = "There is already a category with the given identifier.";
            }
        }
    }

    private function getCategoryByValue($value)
    {
        if (strlen($value) > 0) {

            App::app()->db()->where("value", $value);

            $c = App::app()->db()->getOne($this->getTable());
            if ($c) {
                return $c;
            } else {
                return false;
            }
        }
        return false;
    }

    private function addNewCategory($name, $value)
    {
        if (count($name) > 0) {

            $ok = false;

            foreach ($name as $n) {
                if (strlen($n) > 0) $ok = true;
            }

            if ($ok == false) {
                $this->errors[] = "The name field can not be empty. At least in one of the languages you have to provide.";
                return;
            }

            if ($this->getCategoryByValue($value)) {
                $this->errors[] = "There is already a category with the given identifier.";
                return;
            }

            $n = $this->generateName($name);

            $data = array("name" => json_encode($name), "value" => $value);

            $ok = App::app()->db()->insert($this->getTable(), $data);

            if ($ok) {
                $this->messages[] = "The $n (" . $value . ") category was created successfully!";
                $this->clearPostData();
//                App::app()->get("activitytracker")->newCreateActivity(App::app()->get("login")->getUsername(), "created the " . $n . " blog category", self::$_CREATE_ACTIVITY_GETTER, $n, "blogcategories");
            } else {
                $this->errors[] = "Something went wrong creating the category.";
            }
        } else {
            $this->errors[] = "At least in one language you have to save the category!";
        }
    }

    public function handelGETEditAction()
    {
        if (isset($_GET['id']) && isset($_GET['k'])):

            $id = base64_decode($_GET['id']);
            $name = base64_decode($_GET['k']);

            App::app()->db()->where("id", $id);
            $res = App::app()->db()->getOne($this->getTable());

            if ($res) {

                return [
                    "edit" => true,
                    "id" => $id,
                    "category" => json_decode($name, true),
                    "value" => $res['value']
                ];
            }
        endif;

        return null;
    }

    public function handleGETDeleteAction()
    {
        if (isset($_GET['id']) && isset($_GET['k'])):

            $id = $_GET['id'];
            $name = base64_decode($_GET['k']);

            App::app()->db()->where("id", $id);

            $res = App::app()->db()->delete($this->getTable());
            if ($res) {
//                App::app()->get("activitytracker")->newDeleteActivity(App::app()->get("login")->getUsername(), "deleted the " . $name . " blog category", self::$_DELETE_ACTIVITY_GETTER, $name, "blogcategories");
            }
        endif;
    }


    public function getSearch($get)
    {
        App::app()->db()->where("name", "%" . $get . "%", "LIKE");
        return App::app()->db()->get($this->getTable());
    }

    public function clearPostData()
    {
        if (isset($_POST['category'])) {
            $_POST['category'] = null;
        }
        if (isset($_POST['value'])) {
            $_POST['value'] = null;
        }
        if (isset($_POST['sec'])) {
            $_POST['sec'] = null;
        }
        if (isset($_POST["id"])) {
            $_POST['id'] = null;
        }
    }

    public static function tableInstallerScripts()
    {
        return array("CREATE TABLE `tree_plugin_blog_category` (
                          `id` int(11) NOT NULL PRIMARY KEY auto_increment,
                          `name` text NOT NULL,
                          `value` varchar(255) NOT NULL
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }





}
