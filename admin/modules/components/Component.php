<?php
namespace components;
include_once(__DIR__."/../module.php");

use cms\Module\module\module;
use PDO;
use PDOException;

class Component extends module {
    protected $name;
    protected $type;

    public function __construct($moduleName = null, $tableName = null, $moduleId = null) {
        parent::__construct($moduleName, $tableName, $moduleId);
    }

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


    /**
     * Method for getting all current instances for current module
     * @return array
     */
    public function getAllCurrentComponentInstances():array{
        $result = [
            'success' => false,
            'data' => null,
            'error' => null,
        ];
        $instances = [];
        try{
            $sql = "SELECT component_instance FROM " . $this->getTableName() . " WHERE module_id = :module_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(["module_id" => $this->getID()]);
            $instances = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if($instances){
                $result["success"] = true;
                $result["data"] = $instances;
            }else{
                $result["error"] = 'Failed to fetch current instances instances';
            }

        }catch (PDOException $e) {
            $result['error'] = "Error fetching module data: " . $e->getMessage();
        }
        return $result;
    }

    public function getAllCurrentComponentNames():array{
        $result = [
            'success' => false,
            'data' => null,
            'error' => null,
        ];
        $instances = [];
        try{
            $sql = "SELECT component_name FROM " . $this->getTableName() . " WHERE module_id = :module_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(["module_id" => $this->getID()]);
            $instances = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if($instances){
                $result["success"] = true;
                $result["data"] = $instances;
            }else{
                $result["error"] = 'Failed to fetch current instances instances';
            }

        }catch (PDOException $e) {
            $result['error'] = "Error fetching module data: " . $e->getMessage();
        }
        return $result;
    }

    public function getHighestInstance(){
        $getModuleTable = parent::getTableName();
        $result = [
            'success' => false,
            'data' => null,
            'error' => null,
        ];
        try{
            $sql = "SELECT MAX(component_instance) AS highest_instance 
                FROM $getModuleTable 
                WHERE module_id = :module_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':module_id', $moduleId, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($row['highest_instance']){
                $result["success"] = true;
                $result["data"] = $row['highest_instance'];
            }

        }catch (PDOException $e) {
            $result["success"] = false;
            $result["error"] = "Error fetching module data: " . $e->getMessage();
        }
    return $result;
    }


    public static function getLastInstance(int $moduleId, PDO $db){
        $getModuleTable = module::findModuleTableById($moduleId, $db);
        try {
            // Using sql max find last instance
            $sql = "SELECT MAX(component_instance) AS last_instance 
                FROM $getModuleTable 
                WHERE module_id = :module_id";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':module_id', $moduleId, PDO::PARAM_INT);
            $stmt->execute();

            // Fetch the result
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // Return the highest instance or 0 if none is found
            return $row['last_instance'] ?? 1;

        }catch (PDOException $e) {
            echo "Error fetching module data: " . $e->getMessage();
            return null;
        }
    }

}