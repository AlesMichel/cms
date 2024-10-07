<?php
namespace cms\Module\module;
require_once(__DIR__."/../DbConnect/connect.php");
use \cms\DbConnect\connect;
use PDO;
use PDOException;


class module
{

    protected $moduleName;
    protected string $tableName;
    protected int $moduleId;
    protected PDO $db;

    public function __construct($moduleName = null, $tableName = null, $moduleId = null)
    {
        //get db conn
        $this->db = \cms\DbConnect\connect::getInstance()->getConnection();
        //get module name
        switch (true) {
            // Case: Only moduleId is provided
            case ($moduleId !== null):
                $this->moduleId = $moduleId;

                // Fetch module name and table name based on ID
                $status = $this->getNameViaId();
                if ($status['success']) {
                    $this->moduleName = $status['data'];
                    $tableStatus = $this->getTableViaName();
                    if ($tableStatus['success']) {
                        $this->tableName = $tableStatus['data'];
                    } else {
                        echo $tableStatus['error'];
                    }
                } else {
                    echo $status['error'];
                }
                break;

            // Case: Module name is provided (with or without table name)
            case ($moduleName !== null):
                $this->moduleName = $moduleName;
                $status = $this->getIDViaName();
                if ($status['success']) {
                    $this->moduleId = $status['data'];
                }else{
                    echo $status['error'];
                }

                // Use provided table name if available, otherwise fetch it
                if ($tableName !== null) {
                    $this->tableName = $tableName;
                } else {
                    $status = $this->getTableViaName();
                    if ($status['success']) {
                        $this->tableName = $status['data'];
                    } else {
                        echo $status['error'];
                    }
                }
                break;

            // Case: Neither moduleId nor moduleName is provided
            default:
                echo "Module name or ID must be provided.";
                break;
        }
    }

    #region getters
    public function getTableName()
    {
        return $this->tableName;
    }
    public function getID()
    {
        return $this->moduleId;
    }
    public function getName(){
        return $this->moduleName;
    }
    private function getTableViaName(): array
    {
        $result = [
            'success' => false,
            'data' => null,
            'error' => null,
        ];
        try {
            //find module table name from table modules
            $sql = "SELECT module_table FROM `modules` WHERE module_name = :moduleName";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':moduleName' => $this->moduleName]);
            $moduleTableName = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$moduleTableName || !isset($moduleTableName['module_table'])) {
                $result['error'] = 'Module table not found'; // Error if no row was fetched or column is missing
                return $result; // Early return to avoid further execution
            }
            //compare with db
            $queryCheck = $this->db->prepare("SHOW TABLES LIKE :tableName");
            $queryCheck->execute([':tableName' => $moduleTableName['module_table']]);
            $tableExists = $queryCheck->fetch();
            if ($tableExists) {
                $result['data'] = $moduleTableName['module_table'];
                $result['success'] = true;
            }
        } catch (PDOException $e) {
            $result['error'] = "Error fetching module data: " . $e->getMessage();
        }
        return $result;
    }

    private function getIDViaName(): array
    {
        $result = [
            'success' => false,
            'data' => null,
            'error' => null,
        ];
        try {
            $sql = "SELECT id FROM `modules` WHERE module_name = :moduleName";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':moduleName' => $this->moduleName]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $result['data'] = $row['id'];
                $result['success'] = true;
            }else{
                $result['error'] = 'Module Not Found';
            }
        } catch (PDOException $e) {
            $result['error'] = "Error fetching module data: " . $e->getMessage();
        }
        return $result;
    }
    private function getNameViaId(): array
    {
        $result = [
            'success' => false,
            'data' => null,
            'error' => null,
        ];
        try {
            $sql = "SELECT module_name FROM `modules` WHERE id= :id  ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $this->moduleId]);
            $getName = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!empty($getName)) {
                $result['data'] = $getName['module_name'];
                $result['success'] = true;
            } else {
                $result['error'] = "Module name not found";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        return $result;
    }
    #endregion getters

    //processes
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

    public function deleteModule()
    {
        if ($this->tableName != '') {
            try {
                $sql = "DELETE FROM `modules` WHERE module_name = :moduleName";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':moduleName' => $this->moduleName]);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                echo "Entry with module table cannot be deleted";
                exit();
            }
            try {
                $sql = "DROP TABLE IF EXISTS `$this->tableName`";
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
    public static function findModuleTableById(int $moduleId, $db)
    {
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

    public function getModuleComponentList(): array
    {
        $result = [
            'success' => false,
            'data' => null,
            'error' => null,
        ];
        $instance = 0;
        //instance 0 is for component list only, not for data
        if ($this->tableName) {

            try {
                $sql = "SELECT * FROM $this->tableName WHERE module_id = :moduleId AND component_instance = :component_instance";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':moduleId' => $this->moduleId, ':component_instance' => $instance]);
                $moduleComponents = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($moduleComponents)) {

                    $result['data'] = $moduleComponents;
                    $result['success'] = true;

                } else {
                    $result['error'] = "This module has no components";
                }
            } catch (PDOException $e) {
                $result['error'] = "Error fetching module data: " . $e->getMessage();
            }
        } else {
            $result['error'] = "Module table does not exists";
        }
        return $result;
    }
    public static function getModuleDataForInstance(int $moduleId, int $instance, PDO $db)
    {

        // Step 1: Fetch all components that match module ID
        $moduleTableName = self::findModuleTableById($moduleId, $db);
        if ($moduleTableName) {
            try {

                $sql = "SELECT * FROM $moduleTableName WHERE module_id = :moduleId AND component_instance = :component_instance";
                $stmt = $db->prepare($sql);
                $stmt->execute([':moduleId' => $moduleId, ':component_instance' => $instance]);
                $moduleComponents = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($moduleComponents)) {
                    return $moduleComponents;
                } else {
                    return "This module has no components";
                }

            } catch (PDOException $e) {
                echo "Error fetching module data: " . $e->getMessage();
                return null;
            }
        } else {
            return "Module table not found";
        }
    }
    public function getModuleComponentListForEdit(): array
    {
        $result = [
            'success' => false,
            'data' => null,
            'error' => null,
        ];
        if ($this->tableName) {
            $instance = 0;
            try {
                // Step 1: Fetch all component instances that match the given module ID
                $sql = "SELECT * FROM $this->tableName
                WHERE module_id = :moduleId AND component_instance = :component_instance";

                $stmt = $this->db->prepare($sql);
                $stmt->execute([':moduleId' => $this->moduleId, ':component_instance' => $instance]);

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
                //if components were found
                if (!empty($moduleComponents)) {
                    $result['data'] = $moduleComponents;
                    $result['success'] = true;
                } else {
                    $result['error'] = "This module has no components";
                }

            } catch (PDOException $e) {
                $result['error'] = "Error fetching module data: " . $e->getMessage();
            }
        } else {
            $result['error'] = "Module table not found";
        }
        return $result;
    }
    public function getModuleData(): array|string
    {


        if ($this->tableName && $this->moduleId) {
            try {
                // Step 1: Fetch all component instances that match the given module ID
                $sql = "SELECT * FROM $this->tableName
                WHERE module_id = :moduleId
                ORDER BY component_instance ";

                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':moduleId', $this->moduleId, PDO::PARAM_INT);
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
                        'component_name' => $componentName,
                        'component_instance' => $instance
                    ];
                }

                // Return the organized data
                return $moduleComponents;

            } catch (PDOException $e) {
                return "Error fetching module data: " . $e->getMessage();
            }
        } else {
            return "Module table not found";

        }
    }

}