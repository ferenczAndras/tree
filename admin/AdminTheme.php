<?php
namespace admin;

use admin\components\AdminActivityTracker;
use admin\components\AdminThemeAssets;
use admin\controller\LoginController;
use admin\controller\NotFoundController;
use admin\controller\PasswordResetController;
use admin\controller\RegisterController;
use admin\controller\SystemErrorController;
use tree\App;
use tree\components\admin\Login;

if (!defined('ABSPATH') || !defined('ADMINPATH')) {
    exit;
}

/**
 * Class      AdminTheme
 * @category  Admin Theme Main Theme File
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
class AdminTheme extends \tree\core\AdminTheme
{

    public static $WORDING = "TreeAdmin";

    /**
     * AdminTheme constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setAssets(new AdminThemeAssets());

        App::app()->initActivityTracker(new AdminActivityTracker());

        App::app()->initLogin(new Login());

        App::app()->activePlugins()->load(true);

        $this->dir = __DIR__;
    }

    /**
     *
     */
    public function runTheme()
    {
        $page = App::app()->getPage();

        $c = ucfirst($page) . 'Controller';

        if ($page == "register" || $page == "passwordreset" || $page == "login") {
            $c = 'HomeController';
        }

        if (class_exists('\admin\controller\\' . $c)) {
            $c = '\admin\controller\\' . $c;
            $a = new $c();
            $a->runAction();

        } elseif (class_exists('\admin\controller\\' . $c)) {
            $c = '\admin\controller\\' . $c;
            $a = new $c();
            $a->runAction();

        } else {
            $a = new NotFoundController();
            $a->runAction();
        }

    }


    public function redirectToLoginPage()
    {

        $page = App::app()->getPage();

        if ($page == "passwordreset" && App::app()->settings()->canFreeResetPassword()) {

            if (App::app()->login()->passwordResetWasSuccessful() == true && App::app()->login()->passwordResetLinkIsValid() != true) {
                $h = new LoginController();
                $h->runAction();
            } else {
                $h = new PasswordResetController();
                $h->runAction();
            }

        } else if ($page == "register" && App::app()->settings()->canFreeRegister()) {

            $h = new RegisterController();
            $h->runAction();

        } else if ($page == "systemerror") {

            $h = new SystemErrorController();
            $h->runAction();

        } else {
            $h = new LoginController();
            $h->runAction();
        }
    }

}