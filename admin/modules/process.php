<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use phpCms\Module\module;

session_start();

include("../../src/DbConnect/connect.php");

$db = \phpCms\DbConnect\connect::getInstance()->getConnection();

if(isset($_POST["create"])) {

    $moduleName = trim($_POST["moduleName"]);
    $tableName = trim($_POST["tableName"]);

    $newModule = new module($moduleName, $tableName);

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
if(isset($_POST["delete"])){
    echo $_POST["moduleName"] . "</br>";
    $moduleName = $_POST["moduleName"];
    $deleteModule = \phpCms\Module\module::deleteModule($moduleName, $db);
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





