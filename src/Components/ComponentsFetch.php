<?php

include("TextField.php");

class ComponentsFetch {

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

    public static function createComponent($componentId, $currentModule): string
    {
        //new out
        $out = '';


        //build field
        if($componentId === null) {
            echo "No component found";
        }elseif($componentId == 1) {
            $out .= TextField::getFields();

        }elseif($componentId == 2) {
            $out .= 'Image';
        } elseif($componentId == 3) {
            $out .= 'Position';
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

    public static function createNewComponent($componentId, $currentModule, $db){
       $componentType = self::findComponentTypeById($componentId, $db);
       $out = '';
       if($componentType == 'text'){
         $out .= TextField::getFields();
       }else{
           $out .= 'No fields found';
       }
       return $out;
    }

    public static function insertComponentData($componentId, $componentName, $db): string
    {
        $componentType = self::findComponentTypeById($componentId, $db);
        $out = '';
        if($componentType == 'text'){
            $out .= TextField::getDataFields($componentId,$componentName);
        }else{
            $out .= 'No data fields found';
        }
        return $out;
    }

    public static function editComponentData($componentId, $componentName, $componentData, $db): string
    {
        $componentType = self::findComponentTypeById($componentId, $db);
        $out = '';
        if($componentType == 'text'){
            $out .= TextField::getDataFieldsForEdit($componentId,$componentName, $componentData);
        }else{
            $out .= 'No data fields found';
        }
        return $out;
    }

}
