<?php

namespace phpCms\Components;
//common components class


use PDO;
use PDOException;

abstract class Component
{
    protected $name;
    protected $type;

    public function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;

    }

    abstract public function render();

    public static function getComponentById($id, $db)
    {
        try {
            $sql = "SELECT * FROM components WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute(["id" => $id]);
            $fetchComponent = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($fetchComponent) {
                return $fetchComponent;
            }
        } catch (PDOException $e) {
            echo "Component not found, Error: " . $e->getMessage();
        }
        return '';
    }

    public static function editComponentData($id ,$moduleId, $componentId, $instance, $newData, $db)
    {
        try {
            $sql = "UPDATE module_components 
                SET component_data = :new_data 
                WHERE id = :id
                  AND module_id = :module_id 
                  AND component_instance = :instance 
                  AND component_id = :component_id";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                "id" => $id,
                "new_data" => $newData,
                "module_id" => $moduleId,
                "instance" => $instance,
                "component_id" => $componentId
            ]);
            echo "Data updated successfully.";
        } catch (PDOException $e) {
            echo "Data not updated: " . $e->getMessage();
        }
    }

}