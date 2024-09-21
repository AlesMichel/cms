<?php
//connect to db
include("../../src/DbConnect/connect.php");
include "../../src/Module/module.php";
$db = \phpCms\DbConnect\connect::getInstance()->getConnection();
$moduleName = $_GET["name"];
$getTable = \phpCms\Module\module::findModuleByName($moduleName, $db);
if ($moduleName) {

    echo "GET module name =" . $moduleName . "</br>";
    echo "GET table name =" . $getTable . "</br>";

    try {

        if ($getTable) {
            echo "Module name: $getTable";

            $sqlGetData = "SELECT * FROM `$getTable`";
            $stmtGetData = $db->prepare($sqlGetData);
            $stmtGetData->execute();

            $data = $stmtGetData->fetchAll(PDO::FETCH_ASSOC);

            foreach ($data as $row) {
                echo "<div>";
                echo "ID: " . $row['id'] . "<br>";
                echo "Name: " . htmlspecialchars($row['moduleName']);
                echo "</div><br>";
            }
        } else {

            echo "Table $moduleName does not exist";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

}