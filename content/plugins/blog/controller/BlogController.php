<?php
namespace app\admin\controller;

use app\admin\AdminApplication as App;
use app\admin\model\BlogModel;
use tree\components\Controller;

/**
 * Class BlogController
 * @category  Admin panel controller
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 */
class BlogController extends Controller
{


    public function __construct()
    {
        $this->footer = false;
        $this->actions = array("index", "delete", "ajax", "new", "edit");

        $assets = App::app()->assets;
        $assets->addCss("assets/posts/posts.css");

        $this->setAssets($assets);
        $this->setDir(App::app()->get("dir"));
        $this->model = new BlogModel();
    }

    public function actionIndex()
    {
        $this->renderView("blog/index");
    }

    public function actionNew()
    {
        $param = array();

        if (!empty($this->model->errors)) {
            $param['errors'] = $this->model->errors;
        }
        if (!empty($this->model->messages)) {
            $param['messages'] = $this->model->messages;
        }

        if (empty($this->model->errors) && !empty($this->model->messages)) {

            $this->renderView("blog/index");

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

            $this->renderView("blog/index", $param);

        } else {

            $post = $this->model->getEditPostByGet();

            if ($post) {
                $param['post'] = $post;
                $param['post']['settings'] = json_decode($post['settings'], true);

                $this->renderView("blog/edit", $param);
            } else {

                $this->renderView("blog/index", $param);

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

        $this->renderView("blog/index", $params);
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
                    "urlendparam" => "sec=" . base64_encode(BlogModel::$_SEC_KEY),
                    "results" => $res
                ];
        }
        return null;
    }


    public static $navbar = [

        "type" => "normal",
        "icon" => "fa fa-rss",
        "url" => "",
        "title" => "Blog",
        "items" => [
            [
                "title" => "Blog Posts",
                "url" => "blog"
            ],
            [
                "title" => "New Blog Post",
                "url" => "blog/new"
            ],
            [
                "title" => "Blog Categories",
                "url" => "blogcategories"
            ]
        ]
    ];

}