<?php

define("ABSPATH", __DIR__);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . "/framework/autoload.php";

$app = new \tree\App();
$app->run(\tree\App::$APP_THEME);