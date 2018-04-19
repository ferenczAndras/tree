<?php

namespace plugin\transportmures;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

use tree\App;
use tree\core\Plugin;

class Blog extends Plugin
{

    const VERSION = '1.0.0';

    public function __construct()
    {
        $this->setDir(__DIR__);
        self::initPlugin($this);
    }

    public function runPluginHookBeforeTheme()
    {

    }


    public function runPluginHookBeforeAdmin()
    {
        $page = App::app()->getPage();

        $c = ucfirst($page) . 'Controller';

        if (class_exists('\plugin\blog\controller\admin\\' . $c)) {
            $c = '\plugin\blog\controller\admin\\' . $c;
            $a = new $c();
            $a->runAction();

            // we have to say not to use the themes controllers at all;
            App::app()->theme()->updateRunThemeAfterPlugin(false);
        }
    }


    public static function adminNavigationBar()
    {
        return [

            "type" => "normal",
            "icon" => "fa fa-file",
            "url" => "#",
            "title" => "Blog",
            "items" => [
                [
                    "title" => "Posts",
                    "url" => "blog"
                ]
            ],


        ];
    }


    public static function adminConfig()
    {
        return array(
            "name" => "Blog Plugin",
            "description" => "This plugin provides all the Blog related Methodology for the Tree Framework",
            "identifier" => "blog",
            "adminUrl" => "blog",
            "developer" => "Affarit Studio",
            "developerIdentifier" => "affarit",
            "pluginUrl" => "http://www.affarit.com",
            "version" => self::VERSION,
            "required" => true,
            "tags" => ["blog", "core"]
        );

    }


}