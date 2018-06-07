<?php

namespace theme\bootstrap;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

use theme\bootstrap\controller\NotFoundController;
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

        if (class_exists('\theme\bootstrap\controller\\' . $c)) {
            $c = '\theme\bootstrap\controller\\' . $c;
            $a = new $c();
            $a->runAction();

        } else {
            $a = new NotFoundController();
            $a->runAction();
        }


    }

}