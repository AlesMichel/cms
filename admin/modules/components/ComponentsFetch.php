<?php

namespace components\ComponentsFetch;
require_once(__DIR__."/TextField.php");
require_once(__DIR__."/Image.php");
require_once(__DIR__."/Position.php");

use components\Component;
use components\Image\Image;
use components\TextField\TextField;
use components\Position\Position;
use PDO;
use PDOException;

class ComponentsFetch extends Component {

    public function __construct($moduleName = null, $tableName = null, $moduleId = null) {
        parent::__construct($moduleName, $tableName, $moduleId);
    }

    public static function fetchAllComponents($db) {
        try {
            $sql = 'SELECT * FROM components';
            $stmt = $db->prepare($sql);
            $stmt->execute(); // Execute the query
            $fetchAllComponents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
        return $fetchAllComponents;
    }

    //renders select box with all components available
    public static function renderComponents($db): string {
        // Fetch all components from the database
        $data = self::fetchAllComponents($db);
        if ($data === null) {
            return '<p>No components found.</p>';
        }
        $out = '';
        $out .= '<label for="component">Komponenta</label>
                 <select class="form-select" name="component_id" id="createComponentSelect" onchange="handleDynamicForm()">';
        // Loop through each component and create an option element
        $out .= '<option selected>-</option>';
        foreach ($data as $component) {
            $out .= '<option value="' . htmlspecialchars($component['id']) . '">' .
                htmlspecialchars($component['component_name']) .
                '</option>';
        }
        $out .= '</select>';
        $out .= '<div id="dynamic-fields"></div>';
        return $out;
    }

    public static function createComponent($componentId): string
    {
        //new out
        $out = '';

        //build field
        if($componentId === null) {
            echo "No component found";
        }else if($componentId == 1) {
            $out .= TextField::getFields();
        }elseif($componentId == 2) {
            $out .= Image::getFields();
        } elseif($componentId == 3) {
            $out .= Position::getFields();
        }

        return $out;
    }
    public static function printComponentTable($componentId, $componentName, $db):string{
        $componentType = self::findComponentTypeById($componentId, $db);
        return '<table class="table table-bordered">
                <tr>
                    <td>Název komponenty</td>
                    <td>'. $componentName .'</td>
                </tr>
                <tr>
                    <td>Typ komponenty</td>
                    <td>'. $componentType .'</td>
                </tr>

            </table>';
    }


    public static function findComponentTypeById($componentId, $db){
        try{
            $sql = 'SELECT component_type FROM components WHERE id = :component_id';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':component_id', $componentId);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $componentType = $row["component_type"];
            if($componentType){
                return $componentType;
            }else{
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function getComponentType($componentId){
        try{
            $sql = 'SELECT component_type FROM components WHERE id = :component_id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':component_id', $componentId);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $componentType = $row["component_type"];
            if($componentType){
                return $componentType;
            }else{
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $componentType;
    }
    public function getComponentFields($insertArray, $edit = false): string
    {
        $out = '';
        if($edit){
            foreach($insertArray as $component){
                $getComponentName = $component['component_name'];
                $getComponentId = $component['component_id'];
                $getComponentIsRequired = (int)$component['component_required'];
                $getComponentIsMultlang = (int)$component['component_multlang'];
                $getComponentData = $component['component_data'];
                $getComponentDataEn = $component['component_data_en'];

                $_SESSION["component_pass_data_update"][] = [$getComponentId, $getComponentName];

                if($getComponentId == 1){
                    $textField = new TextField($getComponentName, $getComponentId, $getComponentIsRequired, $getComponentIsMultlang, $getComponentData, $getComponentDataEn);
                    $out .= $textField->getDataFieldsForEdit();
                }else if($getComponentId == 2){
//                    $out .= Image::getDataFieldsForEdit($getComponentName);
                }
                else{
                    $out .= 'No data fields found';
                }
            }
        }
        else{
            foreach($insertArray as $component){
                $getComponentName = $component['component_name'];
                $getComponentId = $component['component_id'];
                $getComponentIsRequired = $component['component_required'];
                $getComponentIsMultlang = $component['component_multlang'];

                if($getComponentId == 1){
                    $textField = new TextField($getComponentName, $getComponentId, $getComponentIsRequired, $getComponentIsMultlang);
                    $out .= $textField->getDataFieldsForInsert();
                }else{
                    $out .= 'No data fields found';
                }
            }
        }


        return $out;
    }


//    public static function insertComponentData($componentId, $componentName, $db): string
//    {
////        $componentType = self::findComponentTypeById($componentId, $db);
//        $componentType = $this->getComponentType($componentId);
//        $out = '';
//        if($componentType == 'text'){
//            $out .= TextField::getDataFieldsForEdit($componentId,$componentName, '');
//        }elseif($componentType == 'image'){
//            $out .= Image::getDataFieldsForEdit($componentId,$componentName, '');
//        }
//        else{
//            $out .= 'No data fields found';
//        }
//        return $out;
//    }

//    public function editComponentData($componentArray): string
//    {
//
////        $componentType = self::findComponentTypeById($componentId, $db);
//        $componentType = $this->getComponentType($componentId);
//        $out = '';
//        if($componentType == 'text'){
//            $textField = new TextField($getComponentName, $getComponentId, $getComponentIsRequired, $getComponentIsMultlang);
//            $out .= $textField->getDataFieldsForInsert();
//        }elseif ($componentType == 'image'){
////            $out .= Image::viewImage($componentData);
//            $out .= Image::getDataFieldsForEdit($componentId,$componentName, $componentData);
//        }elseif($componentType = 'position'){
//            $out .= Position::getDataFieldsForEdit($componentName,$componentData);
//        }
//        else{
//            $out .= 'No data fields found';
//        }
//        return $out;
//    }




}
