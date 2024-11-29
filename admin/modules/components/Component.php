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

        $result = [
            'success' => false,
            'data' => null,
            'error' => null,
        ];
        //var
        $getModuleId = $this->getID();
        $getTableName = $this->getTableName();
        $proceed = true;

        //fetch all instances
        $componentInstancesFetch = $this->getAllCurrentComponentInstances();
        if ($componentInstancesFetch['success'] === true) {
            $componentInstancesAll = $componentInstancesFetch['data'];

            $sql = "INSERT INTO $getTableName (module_id, component_id, component_instance, component_name, component_required, component_multlang) 
                    VALUES (:module_id, :component_id, :instance_id, :component_name, :component_required, :component_multlang)";
            if ($componentInstancesAll == null) {
                //create first component in curr module
                try {
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([
                        ':module_id' => $getModuleId,
                        ':component_id' => $componentId,
                        ':instance_id' => 0,
                        ':component_name' => $componentName,
                        ':component_required' => $componentIsRequired,
                        ':component_multlang' => $componentIsMultlang
                    ]);

                } catch (PDOException $e) {
                    $result['error'] = $e->getMessage();
                }
            } else {
                //ensure that the name is not being already used
                $componentNamesFetch = $this->getAllCurrentComponentNames();
                if($componentNamesFetch['success'] === true){
                    foreach ($componentNamesFetch['data'] as $componentNameFetch) {
                        echo $componentNameFetch['component_name'];
                        if($componentNameFetch['component_name'] === $componentName){
                            $_SESSION['cms_message_error'] = 'Komponent se stejným název už existuje';
                            $proceed = false;
                        }
                    }
                }else{
                    //session error
                    //echo temp
                    $result['error'] .= $componentNamesFetch['error'];
                }
                //module has components
                //get unique instances
                $componentInstancesArray = array_column($componentInstancesAll, 'component_instance');
                $componentInstancesUnique = array_unique($componentInstancesArray);

                if (!empty($componentInstancesUnique && $result['error'] == null && $proceed == true)) {
                    foreach ($componentInstancesUnique as $componentInstance) {

                        try {
                            $stmt = $this->db->prepare($sql);
                            $stmt->execute([
                                ':module_id' => $getModuleId,
                                ':component_id' => $componentId,
                                ':instance_id' => $componentInstance,
                                ':component_name' => $componentName,
                                ':component_multlang' => $componentIsMultlang,
                                ':component_required' => $componentIsRequired
                            ]);
                        } catch (PDOException $e) {
                            $result['error'] .= $e->getMessage();
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
    public function insertDataIntoComponent($data){
        foreach ($data as $component){
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


}