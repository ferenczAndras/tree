<?php
/**
 *            Email components autoload
 * @category  Email components
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

require_once __DIR__ . '/classes/PHPMailer.php';
require_once __DIR__ . '/classes/Stmp.php';
require_once __DIR__ . '/classes/Webhook.php';
require_once __DIR__ . '/classes/MailChimp.php';
require_once __DIR__ . '/classes/Batch.php';

require_once __DIR__ . '/generator/EmailGenerator.php';