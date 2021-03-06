<?php
/**
 *            Framework autoload
 * @category  Tree Framework main file
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

define("TREEDIR", __DIR__);

define("CONTENT", "content");

define("UPDATES", "updates");

define("PLUGINS", "plugins");

define("CONTENTPATH", ABSPATH . DIRECTORY_SEPARATOR . CONTENT);

define("UPDATESPATH", ABSPATH . DIRECTORY_SEPARATOR . CONTENT . DIRECTORY_SEPARATOR . UPDATES . DIRECTORY_SEPARATOR);

define("TREEURL", "http://treeframework.affarit.com");

define("TREEURLGITHUB", "https://github.com/ferenczAndras/tree");

define("FRAMEWORK_URL", "tree/framework");

require_once __DIR__ . '/components/autoload.php';

require_once __DIR__ . '/App.php';
