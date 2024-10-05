<?php
require_once(__DIR__."/module.php");
require_once(__DIR__."/../DbConnect/connect.php");
use cms\Module\module\module;
$db = \cms\DbConnect\connect::getInstance()->getConnection();
include("../templates/cmsDefaultPage.class.php");
session_start();

//remake for session adn action like components/process.php

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $moduleName = $_SESSION['module_name'];
    $action = $_POST['action'];
    $module = new Module($moduleName);

    if($action == 'delete'){
        $deleteModule = $module->deleteModule();
        if($deleteModule) {
            $_SESSION['cms_message'] = 'Module has been deleted';
            header("location: ../modules/index.php");
        }else{
            $_SESSION['cms_message'] = 'Module has been not deleted';
        }
    }
    if($action == 'create'){
        $moduleTableName = $_POST["module_table_name"];
        $newModule = new module($moduleName, $moduleTableName);

        $moduleNameInsert = $newModule->addModuleToModules();
        $moduleTableInsert = $newModule->addModuleToDB();

        if($moduleTableInsert && $moduleNameInsert) {
            $SESSION['cms_message'] = "Module created successfully";
        }else{
            $_SESSION['cms_message_error'] = 'Module has been not created';
        }
        header("location: ../modules/index.php");
        exit();
    }
}

if(isset($_POST["view"])){
    echo $_POST["moduleName"];
    $moduleName = $_POST["moduleName"];

}





