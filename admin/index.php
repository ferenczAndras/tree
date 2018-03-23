<?php
/**
 *            Admin Application index file
 * @category  App index file
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2018 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */


define("ADMIN_FOLDER", "admin");

define("ABSPATH", str_replace(ADMIN_FOLDER, "", __DIR__));

define("ADMINPATH", __DIR__);

require_once __DIR__ . './../config/config.php';

require_once __DIR__ . "./../framework/autoload.php";

require_once __DIR__ . '/autoload.php';

$app = new tree\App(tree\App::$APP_ADMIN);
$app->run();