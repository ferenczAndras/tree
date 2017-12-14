<?php
namespace tree\core;

/**
 * No direct access to this file.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class      PluginController abstract class
 * @category  Core Components
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
abstract class PluginController extends Controller
{

    protected $pluginDirectory;

    protected function setPluginDirectory($dir)
    {
        $this->pluginDirectory = $dir;
    }


    private function getDir()
    {
        return $this->pluginDirectory;
    }

    /**
     * Renders the view from views folder
     * First param is the page itself
     * Second param any necessary data to view file
     * @param $file string
     * @param $param mixed
     */
    public function renderView($file, $param = NULl)
    {
        if (file_exists($this->getDir() . '/view/' . $file . '.php')) {
            require_once $this->getDir() . '/view/' . $file . '.php';
        } else {
            self::error('The <strong>"' . $file . '"</strong> view in ' . get_class($this) . ' dose not exists! ');
        }
    }

}