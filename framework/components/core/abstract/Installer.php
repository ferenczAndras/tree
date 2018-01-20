<?php
namespace tree\core;

/**
 * No direct access to this file.
 */

if (!defined('ABSPATH')) {
    exit;
}


/**
 * Class      Installer abstract class
 * @category  Core Components
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
abstract class Installer extends DatabaseModel
{
    /**
     * @var array holds all the sql scripts that needs to be installed
     */
    private $installScripts = array();

    /**
     * This method registers a new install script
     * @param $sqlScript string sql script which will be executed in order to install the database
     */
    public function registerNewInstallScript($sqlScript)
    {
        $this->installScripts[] = $sqlScript;
    }

    /**
     * This method registers a new install script
     * @param $sqlScriptsArray array list of sqlScripts
     */
    public function registerNewInstallScriptArray($sqlScriptsArray)
    {
        foreach ($sqlScriptsArray as $sqlScript) {
            $this->registerNewInstallScript($sqlScript);
        }
    }

    /**
     * Handles the install it self. Runs thru all the installScripts
     */
    public function install()
    {
        foreach ($this->installScripts as $sqlScript) {
            $this->insertIntoDataBase($sqlScript);
        }
    }

    protected function installScriptsForDevOnly()
    {
        return $this->installScripts;
    }

    private function insertIntoDataBase($sqlScript)
    {
        echo "Le kene futtatni: <br/>";
        echo $sqlScript;
        echo "<br/>";
    }

}