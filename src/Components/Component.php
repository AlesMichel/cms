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

    public static function getComponentById($id, $db){
        try{
            $sql = "SELECT * FROM components WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute(["id" => $id]);
            $fetchComponent = $stmt->fetch(PDO::FETCH_ASSOC);
            if($fetchComponent){
                return $fetchComponent;
            }
        }catch (PDOException $e) {
            echo "Component not found, Error: " . $e->getMessage();
        }
        return '';
    }

    //get id of module_components

    public static function getIdModuleComponents($moduleId, $instance){

    }

    public static function editComponentData($moduleId, $componentId, $instance, $newData){

    }

}