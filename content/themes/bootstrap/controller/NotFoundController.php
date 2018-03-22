<?php

namespace theme\defaulttheme\controller;

use app\SiteApplication as App;
use tree\components\Controller;

class NotFoundController extends Controller
{

    public function __construct()
    {
        $this->setDir(App::app()->get("dir"));
        $this->setAssets(App::app()->assets);
    }

    public function actionIndex()
    {
        echo "error";
    }
}