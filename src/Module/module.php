<?php




class module
{

    protected $name;
    protected $tableName;
    private $db;

    public function __construct($name, $tableName)
    {
        $this->name = $name;
        $this->tableName = $tableName;
        $this->db = \phpCms\DbConnect\connect::getInstance()->getConnection();
    }


    public function addModuleToModules(): bool
    {
        $moduleNameAlreadyExists = false;
        try {
            $queryCheck = $this->db->prepare("SELECT * FROM `modules` WHERE `module_name` = :name");
            $queryCheck->bindParam(":name", $this->name);
            $queryCheck->execute();

            $result = $queryCheck->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                echo "Module already exists.";
                return false;
            }
            //module name does not exit so we proceed to inserting module name into database

            $sql = "INSERT INTO `modules` (module_name, module_table) VALUES (:moduleName, :moduleTableName)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':moduleName' => $this->name, ':moduleTableName' => $this->tableName]);


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
                        id INT(11) AUTO_INCREMENT PRIMARY KEY,
                        component_id INT(11),
                        component_name VARCHAR(255)
                )";
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

    public static function deleteModule($moduleName, PDO $db): bool
    {
        //first delete values in module_components

        //deletes module table based on modules->moduleTableName
        //find module table name
        $moduleTableName = self::findModuleByName($moduleName, $db);

        if ($moduleTableName != '') {
            //module table name found, now search for the actual table
            //now we can delete the entry in modules table

            try {
                $sql = "DELETE FROM `modules` WHERE module_name = :moduleName";
                $stmt = $db->prepare($sql);
                $stmt->execute([':moduleName' => $moduleName]);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                echo "Entry with module table cannot be deleted";
                exit();
            }
            //now we can delete the module table
            try {
                $sql = "DROP TABLE IF EXISTS `$moduleTableName`";
                $stmt = $db->prepare($sql);
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
        try{

            $sql = "SELECT * FROM module_components WHERE module_id = :moduleId AND component_instance = :component_instance";
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
    }

    public static function getComponentsForEditing($moduleId, PDO $db): ?array{
        try {
            // Step 1: Fetch all component instances that match the given module ID
            $sql = "SELECT * FROM module_components
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
    }


    public static function getModuleData(int $moduleId, PDO $db): ?array
    {
        try {
            // Step 1: Fetch all component instances that match the given module ID
            $sql = "SELECT * FROM module_components
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
            echo "Error fetching module data: " . $e->getMessage();
            return null;
        }
    }

}