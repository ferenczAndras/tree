<?php
namespace tree\core;

/**
 * No direct access to this file.
 */

use Pagekit\Application\Exception;
use tree\App;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class      Updater abstract class
 * @category  Core Components
 * @since     1.0.0
 * @author    Ferencz Andras <contact@ferenczandras.ro>
 * @copyright Copyright (c) 2016-2017 Affarit Studio
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link      https://github.com/ferenczAndras/tree
 * @link      http://www.affarit.com
 */
abstract class Updater extends Object
{

    /**
     * @var array $updateMessages A string array holding the update session messages
     */
    private $updateMessages = array();

    /**
     * @var null | boolean $updateAvailable Variable holding if there is an update available
     */
    private $updateAvailable = null;

    /**
     * @var mixed $updateVersion Variable holding the new update version
     */
    private $updateVersion = null;

    /**
     * @var mixed $updateReady Variable holding if the new update is ready to being updated
     */
    private $updateReady = null;

    /**
     * @param $message string Saving new message to messages array
     */
    public function addUpdateMessage($message)
    {
        $this->updateMessages[] = $message;
    }

    /**
     * @return array Returns the messages array
     */
    public function getUpdateMessages()
    {
        return $this->updateMessages;
    }

    /**
     * @return bool|null Downloads and saves if there is a new update available
     */
    public function isUpdateAvailable()
    {
        if ($this->updateAvailable === null) {

            $getVersions = file_get_contents(App::getReleasesVersionsUrl(App::CHANNEL));
            if ($getVersions != '') {
                $versionList = explode("\n", $getVersions);
                foreach ($versionList as $newVersion) {
                    if ($newVersion > App::VERSION) {
                        $this->updateAvailable = true;
                        $this->updateVersion = $newVersion;
                    }
                }
            }
        }
        return $this->updateAvailable == null ? false : $this->updateAvailable;
    }

    /**
     * @return mixed Returns the new Update version
     */
    public function getUpdateVersion()
    {
        return $this->updateVersion;
    }

    /**
     * @param $version string version of the update we want to download
     * @param $channel string channel of the update from where we want to download
     * @throws CoreUpdateException if something goes wrong
     * @return bool if the update is downloaded true else false
     */
    public function downloadUpdate($version, $channel)
    {
        //Download The File If We Do Not Have It
        if (!is_file(UPDATESPATH . 'TREE-' . $version . '-' . $channel . '.zip')) {

            $newUpdate = file_get_contents(App::getReleasePackageUrl($version, $channel));

            if (!is_dir(UPDATESPATH)) mkdir(UPDATESPATH);

            $dlHandler = fopen(UPDATESPATH . 'TREE-' . $version . '-' . $channel . '.zip', 'w');

            if (!fwrite($dlHandler, $newUpdate)) {
                throw new CoreUpdateException("Could not save new update. Operation aborted.");
            }

            fclose($dlHandler);
            $this->updateReady = true;
        } else {
            $this->updateReady = true;
        }
        return $this->updateReady == null ? false : $this->updateReady;
    }

    /**
     * @param $version string version of the update we want to delete
     * @param $channel string channel of the update from where we want to delete
     * @throws CoreUpdateException if something goes wrong
     * @return bool if the update is deleted successfully true else false
     */
    private function deleteUpdate($version, $channel)
    {
        try {

            if (is_file(UPDATESPATH . 'TREE-' . $version . '-' . $channel . '.zip')) {
                unlink(UPDATESPATH . 'TREE-' . $version . '-' . $channel . '.zip');

                return true;
            }

            return false;
        } catch (Exception $e) {
            throw new CoreUpdateException("Could not delete update. Operation aborted.");
        }
    }


    public function doUpdate($version, $channel)
    {

        //Open The File And Do Stuff
        $zipHandle = zip_open(UPDATESPATH . 'TREE-' . $version . '-' . $channel . '.zip');
        echo '<ul>';
        while ($aF = zip_read($zipHandle)) {
            $thisFileName = zip_entry_name($aF);
            $thisFileDir = dirname($thisFileName);

            //Continue if its not a file
            if (substr($thisFileName, -1, 1) == '/') continue;


            //Make the directory if we need to...
            if (!is_dir(ABSPATH . '/' . $thisFileDir)) {
                mkdir(ABSPATH . '/' . $thisFileDir);
                echo '<li>Created Directory ' . ABSPATH . $thisFileDir . '</li>';
            }

            //Overwrite the file
            if (!is_dir(ABSPATH . '/' . $thisFileName)) {
                echo '<li>' . $thisFileName . '...........';
                $contents = zip_entry_read($aF, zip_entry_filesize($aF));
                $contents = str_replace("\r\n", "\n", $contents);
                $updateThis = '';

                //If we need to run commands, then do it.
                if ($thisFileName == 'upgrade.php') {
                    $upgradeExec = fopen('upgrade.php', 'w');
                    fwrite($upgradeExec, $contents);
                    fclose($upgradeExec);
                    include('upgrade.php');
                    unlink('upgrade.php');
                    echo ' EXECUTED</li>';
                } else {
                    $updateThis = fopen(ABSPATH . '/' . $thisFileName, 'w');
                    fwrite($updateThis, $contents);
                    fclose($updateThis);
                    unset($contents);
                    echo ' UPDATED</li>';
                }
            }
        }
        echo '</ul>';

        $this->deleteUpdate($version, $channel);

    }

}