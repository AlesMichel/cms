<?php


session_start();

include("../../src/DbConnect/connect.php");
include("../../src/Module/Module.php");
//remake for session adn action like components/process.php
$db = \phpCms\DbConnect\connect::getInstance()->getConnection();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $action = $_POST['action'];
    echo $action;

    if($action == 'delete'){
        $moduleName = $_SESSION["module_name"];
        $deleteModule = module::deleteModule($moduleName, $db);
        if($deleteModule) {
            echo "module deleted";
            header("location: ../modules/index.php");
        }else{
            echo "module not deleted";
        }


    }
    if($action == 'create'){
        $moduleName = $_POST["module_name"];
        $moduleTableName = $_POST["module_table_name"];
        $newModule = new module($moduleName, $moduleTableName );

        $moduleNameInsert = $newModule->addModuleToModules();
        $moduleTableInsert = $newModule->addModuleToDB();

        if($moduleTableInsert && $moduleNameInsert) {
            $SESSION['message'] = "Module created successfully";
        }else{
            $SESSION['error'] = "Error creating module";

        }
        header("location: ../modules/index.php");
        exit();
    }

}


if(isset($_POST["delete"])){
    echo $_POST["moduleName"] . "</br>";
    $moduleName = $_POST["moduleName"];
    $deleteModule = module::deleteModule($moduleName, $db);
    if($deleteModule) {
        echo "module deleted";
        header("location: ../modules/index.php");
    }else{
        echo "module not deleted";
    }


}
if(isset($_POST["view"])){
    echo $_POST["moduleName"];
    $moduleName = $_POST["moduleName"];

}





