<?php
/**
 * Class      Captcha tools
 * @category  Tree Framework Tools
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */

if (!isset($_GET['gggg'])) {
    exit;
}

include 'Captcha.php';
use tree\tools\captcha\Captcha;

$captch = new Captcha();
$captch->init();
$captch->render();