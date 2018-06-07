<?php

namespace theme\bootstrap;


use tree\App;
use tree\core\Assets;

class BootstrapThemeAssets extends Assets
{

    public $css = array("");

    public $headScrip = array("");

    public $footerScript = array("");

    public function __construct()
    {
        parent::__construct();

        $this->setTitle("Bootstrap Theme Example - Tree Framework");

        $this->setBaseUrl(App::getUrl(""));



        $this->setBaseUrl(App::getUrl(""));


        $this->css = array(
            "content/themes/bootstrap/assets/css/linearicons.css",
            "https://fonts.googleapis.com/css?family=Poppins:100,300,500",
            "content/themes/bootstrap/assets/css/owl.carousel.css",
            "content/themes/bootstrap/assets/css/font-awesome.min.css",
            "content/themes/bootstrap/assets/css/nice-select.css",
            "content/themes/bootstrap/assets/css/magnific-popup.css",
            "content/themes/bootstrap/assets/css/bootstrap.css",
            "content/themes/bootstrap/assets/css/main.css",
        );


        $this->footerScript = array(
            "content/themes/bootstrap/assets/js/vendor/jquery-2.2.4.min.js",
            "https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js",
            "content/themes/bootstrap/assets/js/vendor/bootstrap.min.js",
            "content/themes/bootstrap/assets/js/jquery.ajaxchimp.min.js",
            "content/themes/bootstrap/assets/js/owl.carousel.min.js",
            "content/themes/bootstrap/assets/js/jquery.nice-select.min.js",
            "content/themes/bootstrap/assets/js/jquery.magnific-popup.min.js",
            "content/themes/bootstrap/assets/js/jquery.counterup.min.js",
            "content/themes/bootstrap/assets/js/waypoints.min.js",
            "content/themes/bootstrap/assets/js/main.js",
        );


    }

}