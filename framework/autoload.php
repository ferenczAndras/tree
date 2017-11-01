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

define("TREEPATH", __DIR__);

define("CONTENTPATH", ABSPATH . "/content");

define("TREEURL", "http://treeframework.affarit.com");

define("TREEURLGITHUB", "https://github.com/ferenczAndras/tree");

define("FRAMEWORK_URL", "framework");

require_once TREEPATH . '/helpers/autoload.php';

require_once TREEPATH . '/components/autoload.php';

require_once TREEPATH . '/App.php';
