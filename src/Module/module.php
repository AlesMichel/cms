<?php

namespace phpCms\Module;


use PDO;
use PDOException;

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
            $queryCheck = $this->db->prepare("SELECT * FROM `modules` WHERE `moduleName` = :name");
            $queryCheck->bindParam(":name", $this->name);
            $queryCheck->execute();

            $result = $queryCheck->fetch(PDO::FETCH_ASSOC);
//            while ($result) {
//                if ($result['moduleName'] == $this->name) {
//                    //module name already exists in modules
//                    $moduleNameAlreadyExists = true;
//                    echo "Module already exists.";
//                    break;
//
//                }
//            }
            if ($result) {
                echo "Module already exists.";
                return false;
            }
            //module name does not exit so we proceed to inserting module name into database

            $sql = "INSERT INTO `modules` (moduleName, moduleTableName) VALUES (:moduleName, :moduleTableName)";
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
                $sql = "CREATE TABLE IF NOT EXISTS `$this->tableName` (
                id INT(11) AUTO_INCREMENT PRIMARY KEY)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
        return true;
    }

    public static function deleteModule($moduleName, PDO $db): bool
    {

        //deletes module table based on modules->moduleTableName

        //first we try to find the moduleTableName
//        try {
//            $sql = "SELECT moduleTableName FROM `modules` WHERE moduleName = :moduleName";
//            $stmt = $db->prepare($sql);
//            $stmt->execute([':moduleName' => $moduleName]);
//            $moduleTableName = '';
//            $result = $stmt->fetch(PDO::FETCH_ASSOC);
//            if ($result) {
//                $moduleTableName = $result['moduleTableName'];
//
//            } else {
//                echo "Module table not found";
//            }
//            find module table name
        $moduleTableName = self::findModuleByName($moduleName, $db);

        if ($moduleTableName != '') {
            //module table name found, now search for the actual table
            //now we can delete the entry in modules table

            try {
                $sql = "DELETE FROM `modules` WHERE moduleName = :moduleName";
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

    public static function findModuleByName(string $moduleName, PDO $db)
    {

        try {
            //find module table name from table modules
            $sql = "SELECT moduleTableName FROM `modules` WHERE moduleName = :moduleName";
            $stmt = $db->prepare($sql);
            $stmt->execute([':moduleName' => $moduleName]);
            $moduleTableName = '';
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $moduleTableName = $result['moduleTableName'];
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

}