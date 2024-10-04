<?php


session_start();
include("../../src/DbConnect/connect.php");
include("../../src/Module/Module.php");
//remake for session adn action like components/process.php
$db = \phpCms\DbConnect\connect::getInstance()->getConnection();
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
        $newModule = new module($moduleName, $moduleTableName );

        $moduleNameInsert = $newModule->addModuleToModules();
        $moduleTableInsert = $newModule->addModuleToDB();

        if($moduleTableInsert && $moduleNameInsert) {
            $SESSION['cms_message'] = "Module created successfully";
        }else{
            $_SESSION['cms_message_error'] = 'Module has been not deleted';

        }
        header("location: ../modules/index.php");
        exit();
    }

}



if(isset($_POST["view"])){
    echo $_POST["moduleName"];
    $moduleName = $_POST["moduleName"];

}





