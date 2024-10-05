<?php

use cms\Module\module\module;
use components\Component;

session_start();


require_once(__DIR__."/../module.php");
require_once(__DIR__."/ComponentsFetch.php");
require_once(__DIR__."/../../DbConnect/connect.php");
require_once(__DIR__."/../../config.php");


$db = \cms\DbConnect\connect::getInstance()->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //determine if i am creating, updating or deleting
    $action = $_POST["action"] ?? null;
    $moduleId = $_SESSION["current_module_id"] ?? null;
    $module = new Module(null, null, $moduleId);

    if ($action == "create" && $moduleId != null) {
        $componentData = $_POST["component_data"] ?? '';

            $componentName = $_POST["component_name"];
            $componentId = $_SESSION["component_id"];

            $getTableName = $module->getTableName();
            //create a match instance => create fields for all instances
            $lastInstance = component::getLastInstance($moduleId, $db);

            //sql for batch
            if($getTableName){

            $sql = "INSERT INTO $getTableName (module_id, component_id, component_instance, component_data, component_name) 
                    VALUES (:module_id, :component_id, :instance_id, :component_data, :component_name)";

            if($lastInstance){
                for ($i = 0; $i <= $lastInstance; $i++) {
                //batch sql until i match instance
                try {
                    $stmt = $db->prepare($sql);
                    $stmt->execute([
                        ':module_id' => $moduleId,
                        ':component_id' => $componentId,
                        ':instance_id' => $i,
                        ':component_data' => $componentData,
                        ':component_name' => $componentName
                    ]);
                }catch(PDOException $e){
                    echo "Error: " . $e->getMessage();
                }
            }

            }}
            header("Location: ../../modules/index.php");


    } else if ($action == "delete") {
        $componentPassData = $_SESSION['component_pass_data'];
        $componentId = $componentPassData['component_id'];
        $componentName = $componentPassData['component_name'];
        $moduleId = $componentPassData["module_id"];
        $getTableName = $module->getTableName();
        if($getTableName){
            try {
                $sql = "DELETE FROM $getTableName
                WHERE module_id = :module_id
                  AND component_id = :component_id
                  AND component_name = :component_name";

                $stmt = $db->prepare($sql);
                $stmt->execute([
                    ':module_id' => $moduleId,
                    ':component_id' => $componentId,
                    ':component_name' => $componentName
                ]);

                header("Location: ../../modules/index.php");
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }

    //inserting data
    } else if ($action == "insertData") {
        // Ensure the session data is available
        if (isset($_SESSION['component_pass_data'])) {
            $instance = $_SESSION['newInstance'];
            $moduleId = $_SESSION['current_module_id'];
            $componentPassArray = $_SESSION['component_pass_data_insert'];

            // Check if the componentPassArray is an array
            if (is_array($componentPassArray)) {
                try {
                    // Loop through each component in the pass array
                    foreach ($componentPassArray as $component) {
                        // Check if each component is an array
                        if (is_array($component)) {
                            // Access the component ID and name
                            $componentId = $component[0]; // First element: component ID
                            $componentName = $component[1]; // Second element: component Name
                            $componentData = $_POST['component_' . $componentName] ?? null; // get value of field
//                            echo "Component ID: " . htmlspecialchars($componentId) . "<br>";
//                            echo "Component Name: " . htmlspecialchars($componentName) . "<br>";
//                            echo "Input Value: " . htmlspecialchars($componentData) . "<br>";

                            echo "Data size: " . strlen($componentData) . " bytes";

                            // If data is base64, you might need to decode it:
                            if (strpos($componentData, 'data:image/') === 0) {
                                $componentData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $componentData));
                                echo "Decoded image size: " . strlen($componentData) . " bytes";
                            }

                            // Proceed to insert the data into the database


                            $getTableName = module::findModuleTableById($moduleId, $db);
                            echo $componentData;
                            //now we got all field data and we are ready to insert them
                            try {
                                $sql = "INSERT INTO $getTableName
                                (module_id, component_id, component_instance, component_data, component_name) 
                                VALUES
                                (:module_id, :component_id, :instance_id, :component_data, :component_name)";

                                $stmt = $db->prepare($sql);
                                $stmt->execute([
                                    ':module_id' => $moduleId,
                                    ':component_id' => $componentId,
                                    ':instance_id' => $instance,
                                    ':component_data' => $componentData,
                                    ':component_name' => $componentName
                                ]);

                            } catch (PDOException $e) {
                                echo "Error: " . $e->getMessage();
                            }
                        } else {
                            echo "Expected an array for a component but got: " . htmlspecialchars($component) . "<br>";
                        }
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }


            } else {
                echo "No component pass data available.";
            }
        } else {
            echo "No component pass data available.";
        }
        header("Location: ../../modules/index.php");

    }else if($action == "updateData"){
        // Ensure the session data is available
        if (isset($_SESSION['component_pass_data'])) {
//        var_dump($_SESSION['component_pass_data']);
            //get data from session
            $instance = $_SESSION['current_instance'];
            $moduleId = $_SESSION['current_module_id'];
            $componentPassArray = $_SESSION['component_pass_data_update'];

            // Check if the componentPassArray is an array
            if (is_array($componentPassArray)) {
                try {
                    // Loop through each component in the pass array
                    foreach ($componentPassArray as $component) {
                        // Check if each component is an array
                        if (is_array($component)) {
                            // Access the component ID and name
                            $componentId = $component[0]; // First element: component ID
                            $componentName = $component[1]; // Second element: component Name
                            $componentData = $_POST['component_' . $componentName] ?? null;
                            $getTableName = module::findModuleTableById($moduleId, $db);
                            // get value of field
//                            echo "Component ID: " . htmlspecialchars($componentId) . "<br>";
//                            echo "Component Name: " . htmlspecialchars($componentName) . "<br>";
//                            echo "Input Value: " . htmlspecialchars($componentData) . "<br>";


                            //now we got all field data and we are ready to insert them
                            try {


                                $sql = "UPDATE $getTableName 
                                SET component_data = :component_data, 
                                    component_name = :component_name 
                                WHERE module_id = :module_id 
                                AND component_id = :component_id 
                                AND component_instance = :instance_id";

                                $stmt = $db->prepare($sql);
                                $stmt->execute([
                                    ':module_id' => $moduleId,
                                    ':component_id' => $componentId,
                                    ':instance_id' => $instance,
                                    ':component_data' => $componentData,
                                    ':component_name' => $componentName
                                ]);

                            } catch (PDOException $e) {
                                echo "Error: " . $e->getMessage();
                            }

                            header("Location: ../../modules/index.php");
                        } else {
                            echo "Expected an array for a component but got: " . htmlspecialchars($component) . "<br>";
                        }
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "No component pass data available.";
            }
        } else {
            echo "No component pass data available.";
        }


    }else if($action == 'deleteData'){


        if(isset($_SESSION['component_pass_data'])){
            //        var_dump($_SESSION['component_pass_data']);
            //get instance, get module id
            $instance = $_SESSION['current_instance'];
            $moduleId = $_SESSION['current_module_id'];

            //then delete via it

            $getTableName = $module->getTableName();
            if($getTableName){

                try{
                    $sql = "DELETE FROM $getTableName 
                                WHERE module_id = :module_id 
                                AND component_instance = :instance_id";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([
                        ':module_id' => $moduleId,
                        ':instance_id' => $instance,
                    ]);

                }catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
            //header
            header("Location: ../../modules/index.php");
        }

    } else{
      echo  "Unknown action";
    }

}
