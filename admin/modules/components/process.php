<?php
session_start();
include("../../../src/Module/Module.php");
include("../../../src/Components/ComponentsFetch.php");
include("../../../src/DbConnect/connect.php");
include("../../config.php");
$db = \phpCms\DbConnect\connect::getInstance()->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //determine if i am creating, updating or deleting
    $action = $_POST["action"] ?? null;


    $moduleId = $_SESSION["current_module_id"] ?? null;
    $componentName = $_POST["component_name"];
    $componentId = $_SESSION["component_id"];
    $componentData = $_POST["component_data"] ?? '';
    if ($moduleId) {
        
        if ($action == "create") {
            try {
                $sql = "INSERT INTO module_components (module_id, component_id, component_instance, component_data, component_name) 
                    VALUES (:module_id, :component_id, :instance_id, :component_data, :component_name)";

                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':module_id' => $moduleId,
                    ':component_id' => $componentId,
                    ':instance_id' => 1,
                    ':component_data' => $componentData,
                    ':component_name' => $componentName
                ]);
                header("Location: ../../modules/index.php");
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();

            }
        }

    }
}









//if(isset($_POST['update'])){
//
//
//    $id = $_POST['id'];
//    $moduleId = $_POST['module_id'];
//    $componentId = $_POST['component_id'];
//    $componentInstance = $_POST['component_instance'];
//    $componentDataUpdate = $_POST['component_data_update'];
//
//
//    echo $id . " " . $moduleId . " " . $componentId . " " . $componentInstance . " " . $componentDataUpdate . '  ';
//
//    Component::editComponentData($id,$moduleId, $componentId, $componentInstance, $componentDataUpdate, $db);
//
//
//}