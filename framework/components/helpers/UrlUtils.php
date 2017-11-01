<?php
namespace tree\helper;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class      UrlUtils
 * @category  Helper Class for Core Url handling
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
class UrlUtils
{


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
        if (self::startsWith($what, "http://") || self::startsWith($what, "https://")) {
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

    public static function getClassNameFromString($string, $path)
    {
        $string = str_replace($path, "", $string);
        return str_replace(".php", "", $string);
    }


    public static function error($act, $page = "Dashboard")
    {

        self::redirectInSite('systemerror?action=' . $act . "&backurl=" . $page, true);
    }

    public static function getFrameworkUrl($what)
    {

        if (self::startsWith($what, "http://") || self::startsWith($what, "https://")) {
            return $what;
        }
        $baseUrl = self::getRequestScheme() . "://" . $_SERVER['SERVER_NAME'] . "/" . FRAMEWORK_URL . "/";//. str_replace("index.php", "", $_SERVER['SCRIPT_NAME']);
        return $baseUrl . $what;


    }

    public static function getSiteUrl($what)
    {

        if (self::startsWith($what, "http://") || self::startsWith($what, "https://")) {
            return $what;
        }
        $baseUrl = self::getRequestScheme() . "://" . $_SERVER['SERVER_NAME'] . "/" . SITE_URL . "/";

        return $baseUrl . $what;


    }

    public static function getRequestScheme()
    {
        if (isset($_SERVER['REQUEST_SCHEME']))
            if (self::startsWith($_SERVER['REQUEST_SCHEME'], "http")) return $_SERVER['REQUEST_SCHEME'];
        return "http";
    }


    /**
     * @param $haystack String in which we are searching
     * @param $needle String that we are searching
     * @return bool
     */
    private static function startsWith($haystack, $needle)
    {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

}
