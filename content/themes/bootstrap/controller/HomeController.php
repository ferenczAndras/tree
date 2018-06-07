<?php
namespace theme\bootstrap\controller;


use tree\core\Controller;

class HomeController extends Controller
{


    public function actionIndex()
    {

        $this->renderView("pages/home");
    }

}