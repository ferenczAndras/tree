<?php
/**
 *            Application starter file
 * @category  App index file
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */


define("ABSPATH", __DIR__);

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . "/framework/autoload.php";

use tree\App as App;

$app = new App(App::$APP_THEME);
$app->run();