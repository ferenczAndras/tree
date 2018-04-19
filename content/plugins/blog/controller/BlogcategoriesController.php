<?php
namespace app\admin\controller;

use app\admin\AdminApplication as App;
use app\admin\model\BlogCategoryModel;
use tree\components\Controller;

/**
 * Class BlogCategoriesController
 * @category  Admin panel controller
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 */
class BlogcategoriesController extends Controller
{

    public function __construct()
    {
        $this->footer = false;

        $this->actions = array("index", "edit", "delete");

        $this->setDir(App::app()->get("dir"));
        $this->setAssets(App::app()->assets);

        $this->model = new BlogCategoryModel();
    }

    public function actionIndex()
    {
        $param = array();
        $param['infomessage'] = $this->model->getInfoMessage();

        if (!empty($this->model->errors)) {
            $param['errors'] = $this->model->errors;
        }
        if (!empty($this->model->messages)) {
            $param['messages'] = $this->model->messages;
        }

        $this->renderView("blog/categories", $param);
    }

    public function actionEdit()
    {

        $param = $this->model->handelGETEditAction();

        if (!empty($this->model->errors)) {
            $param['errors'] = $this->model->errors;
        }
        if (!empty($this->model->messages)) {
            $param['messages'] = $this->model->messages;
        }

        $param['infomessage'] = $this->model->getInfoMessage();

        $this->renderView("blog/categories", $param);

    }

    public function actionDelete()
    {
        $param = array();

        $this->model->handleGETDeleteAction();

        if (!empty($this->model->errors)) {
            $param['errors'] = $this->model->errors;
        }
        if (!empty($this->model->messages)) {
            $param['messages'] = $this->model->messages;
        }

        $param['infomessage'] = $this->model->getInfoMessage();

        $this->renderView("blog/categories", $param);
    }


    public static function getSearchResults($get)
    {
        $categoryModel = new BlogCategoryModel();
        $res = $categoryModel->getSearch($get);

        if (count($res) > 0) {
            return
                [
                    "controller" => "Blog Categories",
                    "icon" => "glyphicon glyphicon-list",
                    "url" => "blogcategories",
                    "getter" => BlogCategoryModel::$_SHOW_NAME,
                    "param" => "name",
                    "urlparam" => "name",
                    "results" => $res
                ];
        }
        return null;
    }


}
