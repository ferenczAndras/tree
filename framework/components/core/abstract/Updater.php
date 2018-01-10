<?php
namespace tree\core;

/**
 * No direct access to this file.
 */

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

    private $updateMessages = "";

    private $updateAvailable = null;

    private $updateVersion = null;

    private $updateReady = null;

    public function addUpdateMessage($message)
    {
        $this->updateMessages .= $message;
    }

    public function getUpdateMessages()
    {
        return $this->updateMessages;
    }

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
            if (!is_dir($_ENV['site']['files']['server-root'] . '/' . $thisFileDir)) {
                mkdir($_ENV['site']['files']['server-root'] . '/' . $thisFileDir);
                echo '<li>Created Directory ' . $thisFileDir . '</li>';
            }

            //Overwrite the file
            if (!is_dir($_ENV['site']['files']['server-root'] . '/' . $thisFileName)) {
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
                    $updateThis = fopen($_ENV['site']['files']['server-root'] . '/' . $thisFileName, 'w');
                    fwrite($updateThis, $contents);
                    fclose($updateThis);
                    unset($contents);
                    echo ' UPDATED</li>';
                }
            }
        }
        echo '</ul>';

    }

}