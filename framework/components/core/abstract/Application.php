<?php
namespace tree\core;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}

use tree\database\MySqlDatabase;

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
abstract class Application extends Router
{
    /**
     * This field holds the initialized class. It can be get via:
     *  Application::app()
     * @var Application
     */
    protected static $instance;

    /**
     * Static variable which holds the admin App running key
     * @var string
     */
    public static $APP_ADMIN = "adminApp";

    /**
     * Static variable which holds the normal App running key
     * @var string
     */
    public static $APP_THEME = "themeApp";


    /**
     * Array with application modules
     * @var array
     */
    protected $components = array();


    /**
     * This variable holds the database while the app is running
     * @var \tree\database\MySqlDatabase;
     */
    private $db;

    /**
     * This variable holds the app type which is running currently
     * @var string APP_ADMIN or APP_THEME
     */
    protected $appType = "";


    /**
     * Holds the current theme object
     * @var \tree\core\Theme;
     */
    protected $theme;


    /**
     * Application constructor.
     * @param string $defaultPage default value for page handling
     * @param string $defaultAction default value for action handling
     */
    public function __construct($defaultPage = "home", $defaultAction = "")
    {
        if (!$this->checkPhp()) {
            die("Not supported PHP version.");
        }

        $this->initUrlParams($defaultPage, $defaultAction);
    }

    /**
     * Init the type of the app. We can use this value at plugins and themes to run the correct methods
     * @param $type String
     * @throws IllegalAppTypeException
     */
    public function initAppType($type)
    {
        if ($type === self::$APP_ADMIN || $type === self::$APP_THEME) {
            $this->appType = $type;
        } else {
            throw new IllegalAppTypeException("The $type is not a valid value for running the app.");
        }
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->appType;
    }

    /**
     *
     * @param string $host host name
     * @param string $user user name
     * @param string $pass password for the user
     * @param string $dbName database name
     */
    protected function initMySqlDatabase($host = DB_HOST, $user = DB_USER, $pass = DB_PASS, $dbName = DB_NAME)
    {
        $this->db = new MySqlDatabase ($host, $user, $pass, $dbName);
    }

    /**
     * @return MySqlDatabase
     */
    public function db()
    {
        return $this->db;
    }

    /**
     * @param $logger \tree\core\Logger;
     */
    public function initLogger($logger)
    {
        $this->add("logger", $logger);
    }

    /**
     * @return mixed | \tree\core\Logger
     */
    public function logger()
    {
        return $this->get("logger");
    }


    /**
     * @param $theme \tree\core\Theme;
     * @throws \tree\core\IllegalInitException;
     */
    public function initTheme($theme)
    {
        if ($this->theme != null)
            throw new IllegalInitException("Theme already initialized! You can not override during runtime!");

        $this->theme = $theme;
    }

    /**
     * @return mixed | \tree\core\Theme
     * @throws \tree\core\NotInitializedException;
     */
    public function theme()
    {
        if ($this->theme == null) throw new NotInitializedException("Theme was not initialized!");

        return $this->theme;
    }

    /**
     *
     * Sets the language handler method
     *
     * @param $languageHandler \tree\core\L
     */
    public function initLanguage($languageHandler)
    {
        $this->add("language", $languageHandler);
    }

    /**
     * Get's the current language handler method
     * @return mixed | \tree\core\L
     */
    public function language()
    {
        return $this->get("language");
    }

    /**
     * @param $pluginsManager \tree\pluginmanager\ActivePlugins
     */
    public function initActivePlugins($pluginsManager)
    {
        $this->add("activePlugins", $pluginsManager);
    }

    /**
     * @return null | \tree\pluginmanager\ActivePlugins
     */
    public function activePlugins()
    {
        return $this->get("activePlugins");
    }

    /**
     * @param $settings \tree\core\Settings | \admin\components\AdminSettings;
     */
    public function initSettings($settings)
    {
        $this->add("settings", $settings);
    }

    /**
     * @return \tree\core\Settings | \admin\components\AdminSettings;
     */
    public function settings()
    {
        return $this->get("settings");
    }

    /**
     * @param $login mixed | \tree\components\Login;
     */
    public function initLogin($login)
    {
        $this->add("userLogin", $login);
    }


    /**
     * @return mixed | \tree\components\Login;
     */
    public function login()
    {
        return $this->get("userLogin");
    }

    /**
     * @param $activity \tree\components\ActivityTracker;
     */
    public function initActivityTracker($activity)
    {
        $this->add("activitytracker", $activity);
    }

    /**
     * @return mixed | \tree\components\ActivityTracker;
     */
    public function activityTracker()
    {
        return $this->get("activitytracker");
    }

    /**
     * Saves the module for the String provided
     *
     * It can be an Object, String, Integer or anything.
     *
     * @param $name String
     * @param $object mixed
     */
    public function add($name, $object)
    {
        $this->components[$name] = $object;
    }

    /**
     * Returns a module
     * @param $name string name of the module
     * @return mixed
     */
    public function get($name)
    {
        return isset($this->components[$name]) ? $this->components[$name] : null;
    }

    /**
     *  Main method which handles the requested action
     */
    public function run()
    {
        throw new UnImplementedMethodException('Calling un implemented method: ' . get_class($this) . "::run()");
    }

    /**
     * Sets up the current application object, so later on it can
     * be get via the Application::app()
     * @param $app Application
     */
    public function initApp($app)
    {
        self::$instance = $app;
    }

    /**
     * Returns the Application Object
     * @return Application
     */
    public static function app()
    {
        if (self::$instance != null) {
            return self::$instance;
        } else {
            $c = __CLASS__;
            self::$instance = new $c();
            return self::$instance;
        }
    }

    /**
     * @return string framework directory
     */
    public function frameworkDir()
    {
        return TREEDIR;
    }

    public function frameworkGitHub()
    {
        return TREEURLGITHUB;
    }

    /**
     * Checks if the current php is high enough to run the framework
     *
     * @return boolean
     */
    public function checkPhp()
    {
        return true;
//        return version_compare(PHP_VERSION, '5.3.7', '<');
    }

}