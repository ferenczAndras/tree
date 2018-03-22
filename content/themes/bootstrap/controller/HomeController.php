<?php
namespace theme\defaulttheme\controller;


use tree\core\Controller;

class HomeController extends Controller
{

    public function __construct()
    {
//        $this->setDir(App::app()->get("dir"));
//        $this->setAssets(App::app()->assets);
    }

    public function actionIndex()
    {

        echo get_class($this);
    }

}