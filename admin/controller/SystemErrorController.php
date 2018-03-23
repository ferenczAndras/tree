<?php
namespace admin\controller;

use tree\core\AdminController;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH') || !defined('ADMINPATH')) {
    exit;
}

/**
 * Class      SystemErrorController Admin Theme
 * @category  Admin Theme Controller
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
class SystemErrorController extends AdminController
{
    public function __construct()
    {
        $this->layout = 'empty';
    }

    public function actionIndex()
    {
        $this->renderView("systemerror/systemerror");
    }

}