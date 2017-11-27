<?php
/**
 * Class      FileManager autoload
 * @category  FileManager component
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

/**
 * This value holds the uploads folder path.
 */
define("MEDIA_FILES_PATH_FROM_ROOT", ABSPATH . "/content/uploads/");


try {
    require_once __DIR__ . '/BaseHelper.php';

} catch (Exception $e) {
    echo $e;
}