<?php
namespace plugin\blog\controller\admin;


use app\admin\model\BlogCategoryModel;
use app\admin\model\BlogModel;
use tree\core\PluginInstaller;

class InstallController extends PluginInstaller
{

    function __construct()
    {
        $this->registerNewInstallScriptArray(BlogCategoryModel::tableInstallerScripts());
        $this->registerNewInstallScriptArray(BlogModel::tableInstallerScripts());

        var_dump($this->installScriptsForDevOnly());

    }


}



