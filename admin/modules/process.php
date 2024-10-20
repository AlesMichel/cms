<?php
require_once(__DIR__."/module.php");
require_once(__DIR__."/../DbConnect/connect.php");
use cms\Module\module\module;
$db = \cms\DbConnect\connect::getInstance()->getConnection();
include("../templates/cmsDefaultPage.class.php");


//remake for session adn action like components/process.php

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $moduleName = $_SESSION['module_name'];
    $action = $_POST['action'];


    if($action == 'delete'){

        $module = new Module($moduleName);
        $deleteModule = $module->deleteModule();
        if($deleteModule) {
            $_SESSION['cms_message'] = 'Module has been deleted';
            header("location: ../modules/index.php");
        }else{
            $_SESSION['cms_message'] = 'Module has been not deleted';
        }
    }
    if($action == 'create'){
        $moduleName = $_POST['create_module_name'];
        $moduleTableName = $_POST['create_module_table_name'];
        $newModule = new module($moduleName, $moduleTableName, null);


        header("location: ../modules/index.php");
    }
}

if(isset($_POST["view"])){
    echo $_POST["moduleName"];
    $moduleName = $_POST["moduleName"];

}





