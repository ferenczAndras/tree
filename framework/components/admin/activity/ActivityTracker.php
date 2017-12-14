<?php
namespace tree\components\admin;

/**
 * No direct access to this file.
 */
if (!defined('ABSPATH')) {
    exit;
}


if (!defined("TABLE_ADMIN_ACTIVITY"))
    define("TABLE_ADMIN_ACTIVITY", "tree_site_activity");


use tree\app as App;
use tree\core\DatabaseModel;
use tree\helper\DateUtils;

class ActivityTracker extends DatabaseModel
{

    public $type_create_admin = "create_not_set";
    public $type_delete = "delete_not_set";
    public $type_edit = "edit_not_set";
    public $type_login = "login_activity_not_set";


    private static $_instance;

    public function __construct()
    {
        $this->table = TABLE_ADMIN_ACTIVITY;
        self::$_instance = $this;
    }

    public static function getInstance()
    {
        return self::$_instance;
    }

    private function getCreateActivity($activity)
    {
        $action = json_decode($activity['action'], true);
        $t = DateUtils::time_elapsed_string("@" . $activity['time']);
        return [
            "text" => $action['userName'] . " " . $action['objectName'] . ', ' . $t . ".",
            "icon" => "glyphicon glyphicon-file",
            // "link" => Utils::getUrl($action['url'] . "?" . $action['getterParam'] . "=" . $action['objectId'] . "&u=" . $activity['user_name'] . "&t=" . $t)
            "link" => ""// Utils::getUrl($action['url'] . "?" . $action['getterParam'] . "=" . $action['objectId'] . "&u=" . $activity['user_name'] . "&t=" . $t)

        ];
    }

    private function getDeleteActivity($activity)
    {
        $action = json_decode($activity['action'], true);
        $t = DateUtils::time_elapsed_string("@" . $activity['time']);
        return [
            "text" => $action['userName'] . " " . $action['objectName'] . ', ' . $t . ".",
            "icon" => "fa fa-trash-o",
            //"link" => Utils::getUrl($action['url'] . "?" . $action['getterParam'] . "=" . $action['objectId'] . "&u=" . $activity['user_name'] . "&t=" . $t)
            "link" => ""// Utils::getUrl($action['url'] . "?" . $action['getterParam'] . "=" . $action['objectId'] . "&u=" . $activity['user_name'] . "&t=" . $t)

        ];
    }


    private function getEditActivity($activity)
    {
        $action = json_decode($activity['action'], true);
        $t = DateUtils::time_elapsed_string("@" . $activity['time']);
        return [
            "text" => $action['userName'] . " " . $action['objectName'] . ', ' . $t . ".",
            "icon" => "fa fa-edit",
            //     "link" => Utils::getUrl($action['url'] . "?" . $action['getterParam'] . "=" . $action['objectId'] . "&u=" . $activity['user_name'] . "&t=" . $t)
            "link" => "#"//Utils::getUrl($action['url'] . "?" . $action['getterParam'] . "=" . $action['objectId'] . "&u=" . $activity['user_name'] . "&t=" . $t)
        ];
    }


    public function getActivities($username = false, $type = false, $limit = false)
    {

        if ($username) {
            App::app()->db()->where("user_name", $username);
        }
        if ($type) {
            App::app()->db()->where("type", $type);
        }

        if ($limit) {

        }

        App::app()->db()->orderBy("time", "DESC");
        App::app()->db()->pageLimit = 5;
        $activities = App::app()->db()->get($this->getTable());

        $result = array();

        foreach ($activities as $activity) {

            if ($activity['type'] == $this->type_create_admin) {
                $result[] = $this->getCreateActivity($activity);
            }
            if ($activity['type'] == $this->type_delete) {
                $result[] = $this->getDeleteActivity($activity);
            }
            if ($activity['type'] == $this->type_edit) {
                $result[] = $this->getEditActivity($activity);
            }
        }

        return $result;
    }

    public function getLoginActivities($username = false, $email = false)
    {
        App::app()->db()->where("type", $this->type_login);

        if ($username) {
            App::app()->db()->where("user_name", $username);
        }
        if ($email) {
            App::app()->db()->orWhere("user_name", $email);
        }

        App::app()->db()->orderBy("time", "DESC");
        $activities = App::app()->db()->get($this->getTable());
        return $activities;
    }

    public static $_login_error_type = "erroratlogin";


    public function newLoginActivity($userName, $password, $ip, $message)
    {
        $a = [
            "username" => $userName,
            "password" => $password,
            "type" => $this->type_login,
            "ip" => $ip,
            "message" => $message
        ];

        $action = [
            "type" => $this->type_login,
            "user_name" => $userName,
            "action" => json_encode($a),
            "time" => time()
        ];

        App::app()->db()->insert($this->getTable(), $action);
    }


    public function newEditActivity($objectName, $getterParam, $objectId, $url)
    {

        $userName = App::app()->get("login")->getUsername();

        $a = [
            "userName" => $userName,
            "objectName" => $objectName,
            "objectId" => $objectId,
            "url" => $url,
            "getterParam" => $getterParam
        ];

        $action = [
            "type" => $this->type_edit,
            "user_name" => $userName,
            "action" => json_encode($a),
            "time" => time()
        ];

        App::app()->db()->insert($this->getTable(), $action);
    }


    public function newDeleteActivity($userName, $objectName, $getterParam, $objectId, $url)
    {

        $a = [
            "userName" => $userName,
            "objectName" => $objectName,
            "objectId" => $objectId,
            "url" => $url,
            "getterParam" => $getterParam
        ];

        $action = [
            "type" => $this->type_delete,
            "user_name" => $userName,
            "action" => json_encode($a),
            "time" => time()
        ];

        App::app()->db()->insert($this->getTable(), $action);
    }

    public function newCreateActivity($userName, $objectName, $getterParam, $objectId, $url)
    {

        $a = [
            "userName" => $userName,
            "objectName" => $objectName,
            "objectId" => $objectId,
            "url" => $url,
            "getterParam" => $getterParam
        ];

        $action = [
            "type" => $this->type_create_admin,
            "user_name" => $userName,
            "action" => json_encode($a),
            "time" => time()
        ];

        App::app()->db()->insert($this->getTable(), $action);
    }


    public function getInfoWidget()
    {
        $darab = App::app()->db()->get($this->getTable());

        return [
            [
                "type" => "info-widget",
                "title" => "Number of activity:",
                "subtitle" => "<h3 style='margin-top: 5px;margin-bottom: 2px;'>" . count($darab) . " since installing.</h3>",
                "color" => "black",
                "icon" => "fa fa-line-chart",
                "url" => ""
            ]

        ];
    }

    public function getSettings()
    {
        return null;
    }

}
