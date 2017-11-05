<?php
/**
 *            Core components autoload
 * @category  Core components
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

require_once __DIR__ . '/exception/Exception.php';

require_once __DIR__ . '/abstract/Abstract.php';;

require_once __DIR__ . '/L.php';


require_once __DIR__ . '/Meta.php';
require_once __DIR__ . '/Assets.php';
require_once __DIR__ . '/Settings.php';
require_once __DIR__ . '/Sessions.php';