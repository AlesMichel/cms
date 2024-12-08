<?php
namespace components;
include_once(__DIR__."/../module.php");

use cms\Module\module\module;
use PDO;
use PDOException;

class Component extends module {


    public function __construct($moduleName = null, $tableName = null, $moduleId = null) {
        parent::__construct($moduleName, $tableName, $moduleId);
    }

    /**
     * @param $componentName
     * @param $componentId
     * @param $componentIsRequired
     * @param $componentIsMultlang
     * @return array
     * init new component
     */


    public function initNewComponent($componentName, $componentId, $componentIsRequired, $componentIsMultlang): array
    {
        $sql = "INSERT INTO $this->tableName (module_id, component_id, component_instance, component_name, component_required, component_multlang) 
            VALUES (:module_id, :component_id, :instance_id, :component_name, :component_required, :component_multlang)";

        $result = [
            'success' => false,
            'data' => null,
            'error' => null,
        ];

        $getModuleId = $this->getID();

        // Fetch all current component instances
        $componentInstancesFetch = $this->getAllCurrentComponentInstances();

        if ($componentInstancesFetch['success'] === true) {
            $componentInstancesAll = $componentInstancesFetch['data'];

            // Case 1: No instances found (data === -1)
            if ($componentInstancesAll === -1) {
                try {
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([
                        ':module_id' => $getModuleId,
                        ':component_id' => $componentId,
                        ':instance_id' => 0,
                        ':component_name' => $componentName,
                        ':component_required' => $componentIsRequired,
                        ':component_multlang' => $componentIsMultlang,
                    ]);
                    $result['success'] = true;
                } catch (PDOException $e) {
                    $result['error'] = $e->getMessage();
                }
            } else {
                // Case 2: Instances exist, ensure unique component name
                $componentNamesFetch = $this->getAllCurrentComponentNames();

                if ($componentNamesFetch['success'] === true) {
                    $existingNames = array_column($componentNamesFetch['data'], 'component_name');

                    if (in_array($componentName, $existingNames)) {
                        $_SESSION['cms_message_error'] = 'Komponent se stejným názvem už existuje';
                    } else {
                        // Insert the component for all existing instances
                        try {
                            foreach ($componentInstancesAll as $instance) {
                                $stmt = $this->db->prepare($sql);
                                $stmt->execute([
                                    ':module_id' => $getModuleId,
                                    ':component_id' => $componentId,
                                    ':instance_id' => $instance['component_instance'],
                                    ':component_name' => $componentName,
                                    ':component_required' => $componentIsRequired,
                                    ':component_multlang' => $componentIsMultlang,
                                ]);
                            }
                            $result['success'] = true;
                        } catch (PDOException $e) {
                            $result['error'] = $e->getMessage();
                        }
                    }
                } else {
                    $result['error'] = $componentNamesFetch['error'];
                }
            }
        } else {
            $result['error'] = $componentInstancesFetch['error'];
        }

        return $result;
    }


    /**
     * Method for getting all current names for current module
     * @return array
     */

    public function getAllCurrentComponentNames(): array {
        $result = [
            'success' => false,
            'data' => null,
            'error' => null,
        ];

        try {
            $sql = "SELECT component_name FROM " . $this->getTableName() . " WHERE module_id = :module_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(["module_id" => $this->getID()]);
            $instances = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($instances) { // Query succeeded
                $result['success'] = true;
                $result['data'] = $instances;
            } else {
                $result['error'] .= 'Query failed or returned no results.';
            }
        } catch (PDOException $e) {
            $result['error'] .= "Error fetching module data: " . $e->getMessage();
        }

        return $result;
    }



}