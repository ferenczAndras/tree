<?php

/**
 *            Core components / Exceptions autoload
 * @category  Exceptions components
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

require_once __DIR__ . '/UnknownMethodException.php';
require_once __DIR__ . '/ThemeLoaderException.php';
require_once __DIR__ . '/PluginLoaderException.php';
require_once __DIR__ . '/UnImplementedMethodException.php';
require_once __DIR__ . '/IllegalAppTypeException.php';
require_once __DIR__ . '/EmptyVariableException.php';