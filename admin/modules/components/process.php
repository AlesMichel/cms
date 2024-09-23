<?php

require_once __DIR__ . '/../../../vendor/autoload.php';
use phpCms\Module\module;
use phpCms\Components\Component;
use phpCms\DbConnect\connect;


$db = connect::getInstance()->getConnection();

if(isset($_POST['create'])){
    $moduleName = $_POST['moduleName'];
    $componentName = $_POST['componentName'];
    $componentId = $_POST['componentId'];
    $componentData = "";

    echo $moduleName . " " . $componentName . " " . $componentId . "<br>";

    $moduleId = module::getModuleId($moduleName, $db);
    echo $moduleId . "<br>";

    if ($moduleId) {
        try {
            $sql = "INSERT INTO module_components (module_id, component_id, component_instance, component_data) 
                    VALUES (:module_id, :component_id, :instance_id, :component_data)";

            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':module_id' => $moduleId,
                ':component_id' => $componentId,
                ':instance_id' => 1,
                ':component_data' => $componentData
            ]);
            echo "Component added successfully.";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Module ID not found.";
    }
}

if(isset($_POST['update'])){
    $moduleName = $_POST['module_id'];
    $componentId = $_POST['component_id'];
    $componentInstance = $_POST['component_instance'];
    $componentData = $_POST['component_data'];

    echo $componentData;


}