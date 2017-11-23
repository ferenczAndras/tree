<?php

namespace tree\core;

/**
 * No direct access to this file.
 */
use tree\helper\StringUtils;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class      Application abstract class
 * @category  Core Components
 * @since     1.0.0
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
class Router extends Object
{
    /**
     * Holds the current page slug
     *
     * Example:
     *
     * www.site.com/this-is-a-test-page
     *
     * @var string
     */
    protected $page = "";

    /**
     * @var string
     */
    protected $action = "";

    /**
     * @var array
     */
    protected $tag = array();


    /**
     * @return string
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return array | mixed
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $defaultPage default value for page handling
     * @param string $defaultAction default value for action handling
     */
    protected function initUrlParams($defaultPage = "home", $defaultAction = "")
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : $defaultAction;
        $this->page = isset($_GET['page']) ? $_GET['page'] : $defaultPage;
        $this->page = strtolower($this->page);

        if ($this->page != "systemerror") {
            $this->action = strtolower($this->action);
        }

        $this->tag[] = isset($_GET['tag1']) ? $_GET['tag1'] : "";
        $this->tag[] = isset($_GET['tag2']) ? $_GET['tag2'] : "";
    }


    /**
     * Generates the full url to a path on the website.
     *
     * Example:
     *
     * Utils::getUrl("test") -> (https://) http:// www.yoursite.com/test
     *
     * @param $what | String value
     * @return string
     */
    public static function getUrl($what)
    {
        if (StringUtils::startsWith($what, "http://") || StringUtils::startsWith($what, "https://")) {
            return $what;
        }
        $baseUrl = self::getRequestScheme() . "://" . $_SERVER['SERVER_NAME'] . str_replace("index.php", "", $_SERVER['SCRIPT_NAME']);

        return $baseUrl . $what;

    }

    public static function redirectInSite($url, $permanent = false)
    {
        self::redirect(self::getUrl($url), $permanent);
    }

    public static function redirect($url, $permanent = false)
    {
        header('Location: ' . $url, true, $permanent ? 301 : 302);

        exit();
    }

    public static function error($act, $page = "Dashboard")
    {

        self::redirectInSite('systemerror?action=' . $act . "&backurl=" . $page, true);
    }

    public static function getFrameworkUrl($what)
    {

        if (StringUtils::startsWith($what, "http://") || StringUtils::startsWith($what, "https://")) {
            return $what;
        }
        $baseUrl = self::getRequestScheme() . "://" . $_SERVER['SERVER_NAME'] . "/" . FRAMEWORK_URL . "/";//. str_replace("index.php", "", $_SERVER['SCRIPT_NAME']);
        return $baseUrl . $what;


    }

    public static function getRequestScheme()
    {
        if (isset($_SERVER['REQUEST_SCHEME']))
            if (StringUtils::startsWith($_SERVER['REQUEST_SCHEME'], "http")) return $_SERVER['REQUEST_SCHEME'];
        return "http";
    }


}