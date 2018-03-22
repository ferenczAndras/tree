<?php

namespace theme\bootstrap;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

use tree\App;
use tree\core\Theme;


class BootstrapTheme extends Theme
{

    public function __construct()
    {
        parent::__construct();

        $this->setAssets(new BootstrapThemeAssets());
        $this->setDir(__DIR__);
    }

    public function runTheme()
    {

        $c = ucfirst(App::app()->getPage()) . 'Controller';
//
        echo "RUN THEME CONTROLLER " . $c;

//
//        if (class_exists('\app\controller\core\\' . $c)) {
//            $c = '\app\controller\core\\' . $c;
//            $a = new $c();
//            $a->runAction($this->page, $this->action);
//
//        } elseif (class_exists('\app\controller\\' . $c)) {
//            $c = '\app\controller\\' . $c;
//            $a = new $c();
//            $a->runAction($this->page, $this->action);
//        } else {
//            $a = new NotFoundController();
//            $a->runAction();
//        }


    }

}