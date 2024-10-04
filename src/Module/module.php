<?php




class module
{

    protected $moduleName;
    protected $tableName;
    private PDO $db;

    public function __construct($moduleName, $tableName = null)
    {
        $this->moduleName = $moduleName;

        $this->db = \phpCms\DbConnect\connect::getInstance()->getConnection();

        if($this->tableName == null){
            $this->tableName = $tableName;
        }else{
            $this->tableName = $this->getTableViaName($moduleName);
        }
    }


    public function addModuleToModules(): bool
    {
        $moduleNameAlreadyExists = false;
        try {
            $queryCheck = $this->db->prepare("SELECT * FROM `modules` WHERE `module_name` = :name");
            $queryCheck->bindParam(":name", $this->moduleName);
            $queryCheck->execute();

            $result = $queryCheck->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                echo "Module already exists.";
                return false;
            }
            //module name does not exit so we proceed to inserting module name into database

            $sql = "INSERT INTO `modules` (module_name, module_table) VALUES (:moduleName, :moduleTableName)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':moduleName' => $this->moduleName, ':moduleTableName' => $this->tableName]);


        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
        return true;
    }

    public function addModuleToDB(): bool
    {

        //first check if the table is not already in the database
        try {
            $queryCheck = $this->db->prepare("SHOW TABLES LIKE :tableName");
            $queryCheck->execute([':tableName' => $this->tableName]);
            $tableExists = $queryCheck->fetch();
            if ($tableExists) {
                //table exists
                echo "Table already exists";
                return false;
            } else {
                //table does not exist, proceed to creating new table
                //create id, columns for components
                $sql = "CREATE TABLE IF NOT EXISTS `$this->tableName` (
                        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        module_id INT(11) UNSIGNED,
                        component_id INT(11) UNSIGNED,
                        component_instance INT(11),
                        component_name VARCHAR(255),
                        component_data LONGBLOB,
                        FOREIGN KEY (module_id) REFERENCES modules(id) ON
                            DELETE CASCADE, 
                        FOREIGN KEY (component_id) REFERENCES components(id) ON DELETE CASCADE
                        ) ENGINE=INNODB;";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
        return true;
    }

    public static function getModuleId($moduleName, $db){
        $id = null;
        try{
            $sql = "SELECT id FROM `modules` WHERE module_name = :moduleName";
            $stmt = $db->prepare($sql);
            $stmt->execute([':moduleName' => $moduleName]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result){
                $id = $result['id'];
            }
            return $id;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            echo "Module ID not found.";
        }
        return $id;
    }

    public function deleteModule(){
        //first delete values in module_components

        //deletes module table based on modules->moduleTableName
        //find module table name
        $moduleTableName = $this->getTableViaName($this->moduleName);
        echo $this->moduleName;
        if ($moduleTableName != '') {
            //module table name found, now search for the actual table
            //now we can delete the entry in modules table

            try {
                $sql = "DELETE FROM `modules` WHERE module_name = :moduleName";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':moduleName' => $this->moduleName]);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                echo "Entry with module table cannot be deleted";
                exit();
            }
            //now we can delete the module table
            try {
                $sql = "DROP TABLE IF EXISTS `$moduleTableName`";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                echo "Module table cannot be delete";
                exit();
            }
        } else {
            echo "Module table does not exists";
        }

        return true;
    }

    public static function getModuleNameById(int $id, $db){
        $getName = '';
        try{
            $sql = "SELECT module_name FROM `modules` WHERE id= :id  ";
            $stmt = $db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $getName = $stmt->fetch(PDO::FETCH_ASSOC);
        }catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $getName;
    }

    public static function findModuleTableById(int $moduleId, $db){
        try {
            //find module table name from table modules
            $sql = "SELECT module_table FROM `modules` WHERE id = :moduleId";
            $stmt = $db->prepare($sql);
            $stmt->execute([':moduleId' => $moduleId]);
            $moduleTableName = '';
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $moduleTableName = $result['module_table'];
            } else {
                echo "findModuleByName::Name of module table not found";
            }
            //find modules table in DB
            $queryCheck = $db->prepare("SHOW TABLES LIKE :tableName");
            $queryCheck->execute([':tableName' => $moduleTableName]);
            $tableExists = $queryCheck->fetch();
            if ($tableExists) {
                return $moduleTableName;
            }

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return false;
    }

    public function getTableViaId(int $id){
        try {
            //find module table name from table modules
            $sql = "SELECT module_table FROM `modules` WHERE id = :moduleId";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':moduleId' => $id]);
            $moduleTableName = '';
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $moduleTableName = $result['module_table'];
            } else {
                echo "findModuleByName::Name of module table not found";
            }
            //find modules table in DB
            $queryCheck = $this->db->prepare("SHOW TABLES LIKE :tableName");
            $queryCheck->execute([':tableName' => $moduleTableName]);
            $tableExists = $queryCheck->fetch();
            if ($tableExists) {
                return $moduleTableName;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return false;
    }



    public function getTableViaName(string $moduleName){
        try {
            //find module table name from table modules
            $sql = "SELECT module_table FROM `modules` WHERE module_name = :moduleName";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':moduleName' => $moduleName]);
            $moduleTableName = '';
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $moduleTableName = $result['module_table'];
            } else {
                echo "findModuleByName::Name of module table not found";
            }
            //find modules table in DB
            $queryCheck = $this->db->prepare("SHOW TABLES LIKE :tableName");
            $queryCheck->execute([':tableName' => $moduleTableName]);
            $tableExists = $queryCheck->fetch();
            if ($tableExists) {
                return $moduleTableName;
            }

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return false;
    }

    //maybe rename to findModuleTableByName
    public static function findModuleByName(string $moduleName, PDO $db)
    {

        try {
            //find module table name from table modules
            $sql = "SELECT module_table FROM `modules` WHERE module_name = :moduleName";
            $stmt = $db->prepare($sql);
            $stmt->execute([':moduleName' => $moduleName]);
            $moduleTableName = '';
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $moduleTableName = $result['module_table'];
            } else {
                echo "findModuleByName::Name of module table not found";
            }
            //find modules table in DB
            $queryCheck = $db->prepare("SHOW TABLES LIKE :tableName");
            $queryCheck->execute([':tableName' => $moduleTableName]);
            $tableExists = $queryCheck->fetch();
            if ($tableExists) {
                return $moduleTableName;
            }

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return false;
    }
    public static function getModuleComponents(int $moduleId, PDO $db){
        // Step 1: Fetch all components that match module ID
        $getModuleTable = module::findModuleTableById($moduleId, $db);
        if($getModuleTable){
        try{

            $sql = "SELECT * FROM $getModuleTable WHERE module_id = :moduleId AND component_instance = :component_instance";
            $stmt = $db->prepare($sql);
            $stmt->execute([':moduleId' => $moduleId, ':component_instance' => '1']);
            $moduleComponents = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($moduleComponents)) {
                return $moduleComponents;
            }else{
                return "This module has no components";
            }

        }catch (PDOException $e) {
            echo "Error fetching module data: " . $e->getMessage();
            return null;
        }
        }else{
            return "Module table not found";
        }
    }

    public static function getModuleDataForInstance(int $moduleId, int $instance, PDO $db){

        // Step 1: Fetch all components that match module ID
        $moduleTableName = self::findModuleTableById($moduleId, $db);
        if($moduleTableName){
        try{

            $sql = "SELECT * FROM $moduleTableName WHERE module_id = :moduleId AND component_instance = :component_instance";
            $stmt = $db->prepare($sql);
            $stmt->execute([':moduleId' => $moduleId, ':component_instance' => $instance]);
            $moduleComponents = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($moduleComponents)) {
                return $moduleComponents;
            }else{
                return "This module has no components";
            }

        }catch (PDOException $e) {
            echo "Error fetching module data: " . $e->getMessage();
            return null;
        }
        }else{
            return "Module table not found";
        }
    }

    public static function getComponentsForEditing($moduleId, PDO $db)
    {
        $moduleTableName = self::findModuleTableById($moduleId, $db);
        if($moduleTableName){
        try {
            // Step 1: Fetch all component instances that match the given module ID

            $sql = "SELECT * FROM $moduleTableName
                WHERE module_id = :moduleId AND component_instance = :component_instance";

            $stmt = $db->prepare($sql);
            $stmt->execute([':moduleId' => $moduleId, ':component_instance' => '1']);

            // Step 2: Fetch the data and organize it by component instance
            $moduleComponents = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'];
                $instance = $row['component_instance'];
                $componentId = $row['component_id'];
                $componentData = $row['component_data'];
                $componentName = $row['component_name'];

                // Store the component data, indexed by the instance number
                if (!isset($moduleComponents[$instance])) {
                    $moduleComponents[$instance] = [];
                }
                // Add the component data for this instance
                $moduleComponents[$instance][] = [
                    'id' => $id,
                    'component_id' => $componentId,
                    'component_data' => $componentData,
                    'component_name' => $componentName
                ];
            }
            // Return the organized data
            return $moduleComponents;

        } catch (PDOException $e) {
            echo "Error fetching module data: " . $e->getMessage();
            return null;
        }
    }else{
            return "Module table not found";
        }
    }

    public static function getModuleData(int $moduleId, PDO $db)
    {
        $moduleTableName = self::findModuleTableById($moduleId, $db);
        if($moduleTableName){
        try {
            // Step 1: Fetch all component instances that match the given module ID
            $sql = "SELECT * FROM $moduleTableName
                WHERE module_id = :moduleId
                ORDER BY component_instance ASC";

            $stmt = $db->prepare($sql);
            $stmt->bindParam(':moduleId', $moduleId, PDO::PARAM_INT);
            $stmt->execute();

            // Step 2: Fetch the data and organize it by component instance
            $moduleComponents = [];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'];
                $instance = $row['component_instance'];
                $componentId = $row['component_id'];
                $componentData = $row['component_data'];
                $componentName = $row['component_name'];

                // Store the component data, indexed by the instance number
                if (!isset($moduleComponents[$instance])) {
                    $moduleComponents[$instance] = [];
                }

                // Add the component data for this instance
                $moduleComponents[$instance][] = [
                    'id' => $id,
                    'component_id' => $componentId,
                    'component_data' => $componentData,
                    'component_name' => $componentName
                ];
            }

            // Return the organized data
            return $moduleComponents;

        } catch (PDOException $e) {
            return "Error fetching module data: " . $e->getMessage();
        }
    }
    else{
        return "Module table not found";

    }
}

}