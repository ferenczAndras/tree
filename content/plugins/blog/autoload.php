<?php
/**
 *            TransportMures main autoload
 * @category  TransportMures Plugin
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

require_once __DIR__ . '/model/Model.php';

require_once __DIR__ . '/controller/Controller.php';

require_once __DIR__ . '/Blog.php';
