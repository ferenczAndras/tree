<?php

namespace theme\bootstrap\controller;

use tree\core\Controller;

class NotFoundController extends Controller
{

    public function actionIndex()
    {
        echo "404 error";
    }
}