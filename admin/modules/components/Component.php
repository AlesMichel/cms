<?php

namespace components;
include_once(__DIR__ . "/../module.php");

use cms\Module\module\module;
use PDO;
use PDOException;

class Component extends module
{


    public function __construct($moduleName = null, $tableName = null, $moduleId = null)
    {
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
        $result = [
            'success' => false,
            'data' => null,
            'error' => null,
        ];
        $tableName = $this->getTableName();

        $sql = "INSERT INTO $tableName (module_id, component_id, component_instance, component_name, component_required, component_multlang) 
                    VALUES (:module_id, :component_id, :instance_id, :component_name, :component_required, :component_multlang)";

        //fetch all components
        $componentInstancesFetch = $this->getAllCurrentComponentInstances();


        if ($componentInstancesFetch['error'] === NULL) {

            //not instances were found, create new one
            if ($componentInstancesFetch['data'] === -1) {

                try {
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([
                        ':module_id' => $this->moduleId,
                        ':component_id' => $componentId,
                        ':instance_id' => 0,
                        ':component_name' => $componentName,
                        ':component_required' => $componentIsRequired,
                        ':component_multlang' => $componentIsMultlang
                    ]);

                } catch (PDOException $e) {
                    $result['error'] = $e->getMessage();
                }
            } //instances were found
            else {

                //ensure that the name is not being already used
                $componentInstancesAll = $componentInstancesFetch['data'];
                $componentNamesFetch = $this->getAllCurrentComponentNames();
                if ($componentNamesFetch['success'] === true) {
                    $result['error'] .= "test64";
                    foreach ($componentNamesFetch['data'] as $componentNameFetch) {
                        echo $componentNameFetch['component_name'];
                        if ($componentNameFetch['component_name'] === $componentName) {
                            $_SESSION['cms_message_error'] = 'Komponent se stejným název už existuje';

                        }
                    }
                } else {
                    //session error
                    //echo temp
                    $result['error'] .= $componentNamesFetch['error'];
                }
                //module has components
                //get unique instances
                $componentInstancesArray = array_column($componentInstancesAll, 'component_instance');
                $componentInstancesUnique = array_unique($componentInstancesArray);

                if (!empty($componentInstancesUnique)) {
                    foreach ($componentInstancesUnique as $componentInstance) {
                    
                        try {
                            $stmt = $this->db->prepare($sql);
                            $stmt->execute([
                                ':module_id' => $this->getID(),
                                ':component_id' => $componentId,
                                ':instance_id' => $componentInstance,
                                ':component_name' => $componentName,
                                ':component_multlang' => $componentIsMultlang,
                                ':component_required' => $componentIsRequired
                            ]);
                        } catch (PDOException $e) {
                            $result['error'] .= $e->getMessage();
                            echo $e->getMessage();
                        }

                    }
                }
            }


        }


        return $result;
    }



    /**
     * @param $data
     * @return void
     */
    public function insertDataIntoComponent($data)
    {
        foreach ($data as $component) {
            $componentName = $component['componentName'];
            $componentId = $component['componentId'];
            $componentIsRequired = $component['componentIsRequired'];
            $componentIsMultlang = $component['componentIsMultlang'];
        }
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