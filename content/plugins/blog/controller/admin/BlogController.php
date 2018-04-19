<?php
namespace plugin\blog\controller\admin;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

use plugin\blog\Blog;
use tree\App as App;
use plugin\blog\model\BlogModel;
use tree\core\PluginController;

/**
 * Class BlogController
 * @category  Admin panel controller
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-present Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 */
class BlogController extends PluginController
{

    public function __construct()
    {
        $this->footer = false;
        $this->actions = array("index", "delete", "ajax", "new", "edit");

        App::app()->theme()->getAssets()->addCss("assets/posts/posts.css");

        $this->setPluginDirectory(Blog::plugin()->getDir());

        $this->model = new BlogModel();
    }

    public function actionIndex()
    {
        $this->renderView("blog/blog");
    }

    public function actionNew()
    {
        $param = array();

        if (!empty($this->model->getErrors())) {
            $param['errors'] = $this->model->getErrors();
        }
        if (!empty($this->model->messages)) {
            $param['messages'] = $this->model->getMessages();
        }

        if (empty($this->model->getErrors()) && !empty($this->model->getMessages())) {

            $this->renderView("blog/blog");

        } else {

            $this->footer = false;
            $this->renderView("blog/new", $param);
        }
    }

    public function actionEdit()
    {
        $param = array();

        if (!empty($this->model->errors)) {
            $param['errors'] = $this->model->errors;
        }
        if (!empty($this->model->messages)) {
            $param['messages'] = $this->model->messages;
        }
        if (empty($this->model->errors) && !empty($this->model->messages)) {

            $this->renderView("blog/blog", $param);

        } else {

            $post = $this->model->getEditPostByGet();

            if ($post) {
                $param['post'] = $post;
                $param['post']['settings'] = json_decode($post['settings'], true);

                $this->renderView("blog/edit", $param);
            } else {

                $this->renderView("blog/blog", $param);

            }
        }
    }

    public function actionDelete()
    {

        if ($this->model->handleDelete() == false) {

            $params['errors'] = $this->model->errors;
        } else {
            $params['messages'] = $this->model->messages;
        }

        $this->renderView("blog/blog", $params);
    }

    public function actionAjax()
    {
        $this->layout = "ajax";
        $this->model->handleAjax();
    }


    public static function getSearchResults($get)
    {
        $model = new BlogModel();
        $res = $model->getSearch($get);

        if (count($res) > 0) {
            return
                [
                    "controller" => "Blog Posts",
                    "icon" => "fa fa-rss",
                    "url" => "blog/edit",
                    "getter" => "id",
                    "urlparam" => "id",
                    "param" => "title",
                    "urlendparam" => "sec=" . $model->getSecretKey(),
                    "results" => $res
                ];
        }
        return null;
    }



}