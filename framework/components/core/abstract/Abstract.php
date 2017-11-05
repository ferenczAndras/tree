<?php
/**
 *            Core components / Abstracts autoload
 * @category  Abstracts components
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

require_once __DIR__ . '/Object.php';
require_once __DIR__ . '/DatabaseModel.php';
require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/Router.php';
require_once __DIR__ . '/Application.php';
require_once __DIR__ . '/Plugin.php';
require_once __DIR__ . '/Theme.php';