<?php
include("../../src/DbConnect/connect.php");
include("../../src/Components/ComponentsFetch.php");
$db = \phpCms\DbConnect\connect::getInstance()->getConnection();
$moduleName = $_GET["name"];
if($moduleName){

    try{
        $checkTableQuery = "SHOW TABLES LIKE :tableName";
        $stmtCheckTable = $db->prepare($checkTableQuery);
        $stmtCheckTable->execute(["tableName" => $moduleName]);
        $tableExists = $stmtCheckTable->fetch(PDO::FETCH_ASSOC);

        if($tableExists){
//            echo "Table $moduleName exists";
            echo "Module name: $moduleName </br>";

//            $sqlGetData = "SELECT * FROM `$moduleName`";
//            $stmtGetData = $db->prepare($sqlGetData);
//            $stmtGetData->execute();
//
//            $data = $stmtGetData->fetchAll(PDO::FETCH_ASSOC);
//
//            foreach($data as $row){
//                echo "<div>";
//                echo "ID: " . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "<br>";
//                echo "Name: " . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
//                echo "</div><br>";
//            }

            echo \phpCms\Components\ComponentsFetch::renderComponents($db);
        }else{
            echo "Table $moduleName does not exist";
        }
    }catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

}