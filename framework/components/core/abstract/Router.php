<?php

namespace tree\core;

/**
 * No direct access to this file.
 */
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
        return $this->page;
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


}