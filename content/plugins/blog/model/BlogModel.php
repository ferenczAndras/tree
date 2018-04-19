<?php
namespace app\admin\model;

use app\admin\AdminApplication as App;
use tree\components\BaseModel;

/**
 * Class PostModel
 * @category  Admin panel model
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 */
class BlogModel extends BaseModel
{
    public static $_SEC_KEY = "Change_This_to_A_RaNdOM_Str1ng";
    public static $_instance = null;

    private static $TABLE_NAME = "af_blog";

    public static $_EDIT_ACTIVITY_GETTER = "editname";
    public static $_CREATE_ACTIVITY_GETTER = "createname";
    public static $_SHOW_NAME = "showname";
    public static $_DELETE_ACTIVITY_GETTER = "deletename";


    function __construct()
    {
        parent::__construct();

        $this->handlePost();
        self::$_instance = $this;
    }


    public function rules()
    {
        return array(
            array('attributes' => 'url', 'rule' => 'required', 'error' => 'Url is required!'),
            array('attributes' => 'short_url', 'rule' => 'required', 'error' => 'Short url is required!')
        );
    }

    public function handlePost()
    {
        if (isset($_POST['Post']) && isset($_POST['newpost']) && $this->handleSecurityPOST()) {
            $this->handleNewPost();
        } else if (isset($_POST['Post']) && isset($_POST['editpost']) && $this->handleSecurityPOST()) {
            $this->handleEditPost();
        }

    }

    public function handleDelete()
    {
        if (isset($_GET['id']) && $this->handleSecurityGET()) {
            return $this->deleteBlogPostById(base64_decode($_GET['id']), isset($_GET['n']) ? $_GET['n'] : "");
        }
        return false;
    }

    private function deleteBlogPostById($id, $name)
    {
        $this->_db->where("id", $id);
        $res = $this->_db->delete(self::$TABLE_NAME);

        if ($res) {
            $this->messages[] = $name . " deleted successfully!";
            App::app()->get("activitytracker")->newDeleteActivity(App::app()->get("login")->getUsername(), "deleted the " . $name . " blog post.", self::$_DELETE_ACTIVITY_GETTER, $name, "blog");
            return true;
        } else {
            $this->errors[] = "Something went wrong!";
            return false;
        }
    }

    private function handleNewPost()
    {
        $post = $_POST['Post'];

        if (parent::validateArray($post)) {

            if ($this->checkTitle($post)) {

                if (!$this->getPostByUrl($post['url'], $post['short_url'])) {


                    if (isset($post['category'])) {

                        if (isset($_POST['featuredimage'])) {
                            $post['images'] = '["' . $_POST['featuredimage'] . '"]';
                        }

                        $post['category'] = json_encode($post['category']);
                        $post['content'] = json_encode($post['content']);
                        $n = $this->generateName($post['title']);

                        $post['title'] = json_encode($post['title']);
                        $post['content_one_sentence'] = json_encode($post['content_one_sentence']);


                        $ok = $this->_db->insert(self::$TABLE_NAME, $post);

                        if ($ok) {

                            App::app()->get("activitytracker")->newEditActivity(App::app()->get("login")->getUsername(), "created the " . $n . " blog post", self::$_EDIT_ACTIVITY_GETTER, $post['title'], "blog");
                            $this->messages[] = "Post $n created successfully!";

                        } else {

                            $this->errors[] = "Something went wrong saving the post... Please try again later.";

                        }

                    } else {
                        $this->errors[] = "You have to select at least one category for the post!";
                    }
                } else {
                    $this->errors[] = "The url / short url already exists in the database!";
                }
            } else {
                $this->errors[] = "The title is required!";
            }
        }
    }


    private function handleEditPost()
    {
        $post = $_POST['Post'];

        $id = base64_decode($post['id']);
        $post['id'] = $id;


        if (parent::validateArray($post)) {

            $old = $this->getPostByUrl($post['url'], $post['short_url']);

            if ($old['id'] == $post['id'] || $old['id'] == null) {

                if ($this->checkTitle($post)) {

                    if (isset($post['category'])) {

                        if (isset($_POST['featuredimage'])) {
                            $post['images'] = '["' . $_POST['featuredimage'] . '"]';
                        }

                        $post['category'] = json_encode($post['category']);
                        $post['content'] = json_encode($post['content']);
                        $n = $this->generateName($post['title']);
                        $post['title'] = json_encode($post['title']);
                        $post['content_one_sentence'] = json_encode($post['content_one_sentence']);


                        $this->_db->where("id", $id);

                        $ok = $this->_db->update(self::$TABLE_NAME, $post);

                        if ($ok) {

                            App::app()->get("activitytracker")->newEditActivity(App::app()->get("login")->getUsername(), "edited the " . $n . " blog post", self::$_EDIT_ACTIVITY_GETTER, $post['title'], "blog");

                            $this->messages[] = "Post $n edited successfully!";

                        } else {
                            $this->errors[] = "Something went wrong saving the post... Please try again later.";
                        }
                    } else {
                        $this->errors[] = "You have to select at least one category for the post!";
                    }

                } else {
                    $this->errors[] = "The title is required!";
                }

            } else {
                $this->errors[] = "There is already a post with the same url / short url in the database!";
            }

        }

    }


    private function checkTitle($post)
    {
        $title = $post['title'];
        foreach ($title as $t) {
            if (strlen($t) > 0) return true;
        }
        return false;
    }


    private function handleSecurityPOST()
    {
        if (isset($_POST['sec']) == false) {
            return false;
        }
        if (base64_decode($_POST['sec']) == self::$_SEC_KEY) {
            return true;
        }

        return false;
    }

    private function handleSecurityGET()
    {
        if (isset($_GET['sec']) == false) {
            return false;
        }
        if (base64_decode($_GET['sec']) == self::$_SEC_KEY) {
            return true;
        }

        return false;
    }

    /**
     * @return array|null
     */
    public function getEditPostByGet()
    {

        if (isset($_GET['id']) && $this->handleSecurityGET()) {
            $id = isset($_GET['b']) ? base64_decode($_GET['id']) : $_GET['id'];
            $post = $this->getPostById($id);

            if (isset($post['category'])) {
                $post['category'] = json_decode($post['category'], true);
            }

            if (isset($post['content'])) {
                $post['content'] = json_decode($post['content'], true);
            }
            if (isset($post['title'])) {
                $post['title'] = json_decode($post['title'], true);
            }
            if (isset($post['content_one_sentence'])) {
                $post['content_one_sentence'] = json_decode($post['content_one_sentence'], true);
            }

            return $post;
        }
        return null;
    }

    public function getPostById($id)
    {
        $this->_db->where("id", $id);
        return $this->_db->getOne(self::$TABLE_NAME);
    }

    public function getPostByUrl($url, $short_url)
    {
        $this->_db->where("url", $url);
        $this->_db->orWhere("url", $short_url);
        $this->_db->orWhere("short_url", $short_url);
        $this->_db->orWhere("short_url", $url);

        return $this->_db->getOne(self::$TABLE_NAME);
    }


    public static function getInstance()
    {
        return self::$_instance;
    }

    public function getPosts()
    {
        $this->_db->orderBy("time");
        return $this->_db->get(self::$TABLE_NAME);
    }

    public function getSearch($get)
    {
        $this->_db->where("url", "%" . $get . "%", "LIKE");
        $this->_db->orWhere("short_url", "%" . $get . "%", "LIKE");
        $this->_db->orWhere("content", "%" . $get . "%", "LIKE");
        $this->_db->orWhere("content_short", "%" . $get . "%", "LIKE");
        $this->_db->orWhere("content_one_sentence", "%" . $get . "%", "LIKE");
        $this->_db->orWhere("title", "%" . $get . "%", "LIKE");

        return $this->_db->get(self::$TABLE_NAME);
    }

    public
    function handleAjax()
    {
        $response = array();

        if (isset($_POST['title']) && isset($_POST['generateurl']) && $this->handleSecurityPOST()) {

            $response = $this->generateUrl();
        }

        echo json_encode($response);
        die;
    }

    private
    function generateUrl()
    {
        return [
            str_replace(" ", "-", trim(strtolower(preg_replace('/\s+/', ' ', trim(substr($_POST['title'], 0, 80)))))),
            str_replace(" ", "-", trim(strtolower(preg_replace('/\s+/', ' ', trim(substr($_POST['title'], 0, 25))))))
        ];
    }
}