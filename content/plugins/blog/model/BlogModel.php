<?php
namespace app\admin\model;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

use tree\App as App;
use tree\core\BaseModel;

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

    public static $_EDIT_ACTIVITY_GETTER = "editname";
    public static $_CREATE_ACTIVITY_GETTER = "createname";
    public static $_SHOW_NAME = "showname";
    public static $_DELETE_ACTIVITY_GETTER = "deletename";


    function __construct()
    {
        $this->setTableName("tree_plugin_blog");
        $this->initSecretKey("CHANGE_THIS_TO_A_fgdfdgSECRET_KEY");

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
        if (isset($_POST['Post']) && isset($_POST['newpost']) && $this->handleSecurityFormPost()) {
            $this->handleNewPost();
        } else if (isset($_POST['Post']) && isset($_POST['editpost']) && $this->handleSecurityFormPost()) {
            $this->handleEditPost();
        }

    }

    public function handleDelete()
    {
        if (isset($_GET['id']) && $this->handleSecurityFormGet()) {
            return $this->deleteBlogPostById(base64_decode($_GET['id']), isset($_GET['n']) ? $_GET['n'] : "");
        }
        return false;
    }

    private function deleteBlogPostById($id, $name)
    {
        App::app()->db()->where("id", $id);
        $res = App::app()->db()->delete($this->getTable());

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


                        $ok = App::app()->db()->insert($this->getTable(), $post);

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


                        App::app()->db()->where("id", $id);

                        $ok = App::app()->db()->update($this->getTable(), $post);

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


    /**
     * @return array|null
     */
    public function getEditPostByGet()
    {

        if (isset($_GET['id']) && $this->handleSecurityFormGet()) {
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
        App::app()->db()->where("id", $id);
        return App::app()->db()->getOne($this->getTable());
    }

    public function getPostByUrl($url, $short_url)
    {
        App::app()->db()->where("url", $url);
        App::app()->db()->orWhere("url", $short_url);
        App::app()->db()->orWhere("short_url", $short_url);
        App::app()->db()->orWhere("short_url", $url);

        return App::app()->db()->getOne($this->getTable());
    }


    public static function getInstance()
    {
        return self::$_instance;
    }

    public function getPosts()
    {
        App::app()->db()->orderBy("time");
        return App::app()->db()->get($this->getTable());
    }

    public function getSearch($get)
    {
        App::app()->db()->where("url", "%" . $get . "%", "LIKE");
        App::app()->db()->orWhere("short_url", "%" . $get . "%", "LIKE");
        App::app()->db()->orWhere("content", "%" . $get . "%", "LIKE");
        App::app()->db()->orWhere("content_short", "%" . $get . "%", "LIKE");
        App::app()->db()->orWhere("content_one_sentence", "%" . $get . "%", "LIKE");
        App::app()->db()->orWhere("title", "%" . $get . "%", "LIKE");

        return App::app()->db()->get($this->getTable());
    }

    public
    function handleAjax()
    {
        $response = array();

        if (isset($_POST['title']) && isset($_POST['generateurl']) && $this->handleSecurityFormPost()) {

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


    public static function tableInstallerScripts()
    {
            return array("CREATE TABLE `tree_plugin_blog` (
                          `id` bigint(11) NOT NULL,
                          `url` text NOT NULL,
                          `short_url` text NOT NULL,
                          `time` int(22) NOT NULL,
                          `images` text NOT NULL,
                          `category` text NOT NULL,
                          `content` mediumtext NOT NULL,
                          `content_short` text NOT NULL,
                          `content_one_sentence` text NOT NULL,
                          `title` text NOT NULL,
                          `status` varchar(100) NOT NULL,
                          `settings` mediumtext NOT NULL,
                          `password` varchar(255) NOT NULL,
                          `view_count` bigint(22) NOT NULL
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
                "ALTER TABLE `tree_plugin_blog` ADD PRIMARY KEY (`id`);");
    }
}