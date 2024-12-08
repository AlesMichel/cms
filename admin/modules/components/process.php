<?php

use cms\Module\module\module;
use components\Component;
use components\ComponentsFetch\ComponentsFetch;
use components\Image\Image;

session_start();


require_once(__DIR__ . "/../module.php");
require_once(__DIR__ . "/ComponentsFetch.php");
require_once(__DIR__ . "/../../DbConnect/connect.php");
require_once(__DIR__ . "/../../config.php");


$db = \cms\DbConnect\connect::getInstance()->getConnection();
$proceed = true;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //determine if i am creating, updating or deleting
    $action = $_POST["action"] ?? null;
    $moduleId = $_SESSION["current_module_id"] ?? null;
    $module = new Module(null, null, $moduleId);
    $component = new Component(null, null, $moduleId);
//    echo $component->getName() . $component->getTableName();


    if ($action == "create" && $moduleId != null) {

        $componentData = $_POST["component_data"] ?? '';
        $componentName = $_POST["component_name"];
        $componentId = $_SESSION["component_id"];
        $componentIsRequired = $_POST["component_isRequired"] ?? false;
        $componentIsMultlang = $_POST["component_isMultlang"] ?? false;
        $res = $component->initNewComponent($componentName, $componentId, $componentIsRequired, $componentIsMultlang);

        header("Location: ../../modules/index.php");


    } else if ($action == "delete") {
        $componentPassData = $_SESSION['component_pass_data'];
        $componentId = $componentPassData['component_id'];
        $componentName = $componentPassData['component_name'];
        $moduleId = $componentPassData["module_id"];
        $getTableName = $module->getTableName();
        if ($getTableName) {
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
//
                header("Location: ../../modules/index.php");
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }

        //inserting data
    } else if ($action == "insertData") {
        // Ensure the session data is available
        if (isset($_SESSION['component_pass_data'])) {
            $moduleId = $_SESSION['current_module_id'];
            $componentPassArray = $_SESSION['component_pass_data_insert'];
            $lastInstance = $module->getHighestInstance()['data'];
            $instance = $lastInstance + 1;

             //Check if the componentPassArray is an array
            if (is_array($componentPassArray)) {
                try {
                    // Loop through each component in the pass array
                    foreach ($componentPassArray as $component) {
                        // Check if each component is an array
                        if(is_array($component)){
                            $componentId = $component['component_id'];
                            $componentName = $component['component_name'];
                            $componentIsMultlang = (int)$component['component_multlang'];
                            $componentIsRequired = (int)$component['component_required'];
                            $componentData = $_POST['component_' . $componentName] ?? '';

                            if($componentIsMultlang === 1){
                                $componentDataEn =  $_POST['component_en_' . $componentName];
                            }else{
                                $componentDataEn = '';
                            }

                            if ($componentId === 2) {
                                $image = new \components\Image\Image(null, null, $moduleId);
                                $res = $image->uploadImage($componentData);
                                $componentData = $res['data'];
                            }

                            $getTableName = $module->getTableName();

                            //now we got all field data and we are ready to insert them
                            try {
                                $sql = "INSERT INTO $getTableName
                                (module_id, component_id, component_instance, component_data, component_data_en, component_name, component_multlang, component_required)
                                VALUES
                                (:module_id, :component_id, :instance_id, :component_data, :component_data_en, :component_name, :component_multlang, :component_required)";

                                $stmt = $db->prepare($sql);
                                $stmt->execute([
                                    ':module_id' => $moduleId,
                                    ':component_id' => $componentId,
                                    ':instance_id' => $instance,
                                    ':component_data' => $componentData,
                                    ':component_data_en' => $componentDataEn,
                                    ':component_name' => $componentName,
                                    ':component_multlang' => $componentIsMultlang,
                                    ':component_required' => $componentIsRequired,

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
    } else if ($action == "updateData") {
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
                            $componentData = $_POST['component_' . $componentName] ?? '';
                            $componentDataEn = $_POST['component_en' . $componentName] ?? '';
                            $getTableName = $module->getTableName();

                            if ($componentId === 2) {
                                $image = new \components\Image\Image(null, null, $moduleId);
                                $res = $image->uploadImage($componentData);
                                var_dump($res);
                                $componentData = $res['data'];
                            }

                            //now we got all field data and we are ready to insert them
                            try {


                                $sql = "UPDATE $getTableName 
                                SET component_data = :component_data,
                                    component_data_en = :component_data_en,
                    
                                WHERE module_id = :module_id 
                                AND component_name = :component_name
                                AND component_id = :component_id 
                                AND component_instance = :instance_id";

                                $stmt = $db->prepare($sql);
                                $stmt->execute([
                                    ':module_id' => $moduleId,
                                    ':component_id' => $componentId,
                                    ':instance_id' => $instance,
                                    ':component_data' => $componentData,
                                    ':component_data_en' => $componentDataEn,
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


    } else if ($action == 'deleteData') {


        if (isset($_SESSION['component_pass_data'])) {
            //        var_dump($_SESSION['component_pass_data']);
            //get instance, get module id
            $instance = $_SESSION['current_instance'];
            $moduleId = $_SESSION['current_module_id'];

            $componentPassData = $_SESSION['component_pass_data'];
            //then delete via it

            $getTableName = $module->getTableName();
            if ($getTableName) {
                //delete data from sql
                try {
                    $sql = "DELETE FROM $getTableName
                                WHERE module_id = :module_id
                                AND component_instance = :instance_id";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([
                        ':module_id' => $moduleId,
                        ':instance_id' => $instance,
                    ]);

                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            }
            //get image files that need to be deleted
            $imgFiles = [];
            foreach ($componentPassData as $component) {
                if (is_array($component)) {
                    echo $component['component_id'];
                    if ($component['component_id'] === 2) {
                        $imgFiles[] = $component['component_data'];
                    }

                }
            }
            image::deleteFiles($imgFiles);


            //header
           header("Location: ../../modules/index.php");
        }

    }else if($action === 'editComponent'){


        //update values
        $componentDataPass = $_SESSION['component_pass_data'];

        $moduleId = $componentDataPass['module_id'];
        $component_name = $componentDataPass['component_name'];
        $componentIsRequired = $componentDataPass['component_required'];
        $componentIsMultlang = $componentDataPass['component_multlang'];

        $moduleTableName = $component->getTableName();


        try{

            $sql = "UPDATE $moduleTableName SET component_multlang = :isMultlang, component_required = :isRequired
            WHERE component_instance = :instance AND component_name = :component_name";
            $stmt = $db->prepare($sql);
            $stmt->execute([":isMultlang" => $componentIsMultlang, "isRequired" => $componentIsRequired, ":instance" => '0', ":component_name" => $component_name]);

        }catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        header("Location: ../../modules/index.php");
    }
    else {
        echo "Unknown action";
    }

}
