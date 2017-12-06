<?php
/**
 * Class      config.php
 * @category  core
 * @since     1.0.0
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

/****
 * ------------------------------
 ****/

/**
 * AUTO GENERATED SOURCE FROM THIS POINT PLEASE DO NOT MODIFY
 */

/**
 * This field is used for developments purposes. If your website is live, we suggest to delete from config !
 */

define("TREE_DEVELOPMENT", true);

define("TREE_DEBUG", true);

define("APP_INSTALLED", true);

define("ADMIN_FOLDER", "admin");

require_once __DIR__ . '/db.php';


/****
 * ------------------------------
 ****/

if (defined("ADMINPATH")):

    require_once __DIR__ . '/email.php';
endif;