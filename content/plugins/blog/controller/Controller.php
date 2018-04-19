<?php
/**
 *            Blog Controller autoload
 * @category  Blog plugin
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-present Affarit Studio
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


if (defined('ADMINPATH')):

    require_once __DIR__ . '/admin/BlogcategoriesController.php';

    require_once __DIR__ . '/admin/BlogController.php';

endif;