<?php
include("../templates/header.php");


    //connect to db
    include("../../src/DbConnect/connect.php");
    $db = \phpCms\DbConnect\connect::getInstance()->getConnection();
    $moduleName = $_GET["name"];
//if ($moduleName) {
//
//
//    try {
//        $checkTableQuery = "SHOW TABLES LIKE :tableName";
//        $stmtCheckTable = $db->prepare($checkTableQuery);
//        $stmtCheckTable->execute(["tableName" => $moduleName]);
//        $tableExists = $stmtCheckTable->fetch(PDO::FETCH_ASSOC);
//
//        if ($tableExists) {
////            echo "Table $moduleName exists";
//            echo "Module name: $moduleName";
//
//            $sqlGetData = "SELECT * FROM `$moduleName`";
//            $stmtGetData = $db->prepare($sqlGetData);
//            $stmtGetData->execute();
//
//            $data = $stmtGetData->fetchAll(PDO::FETCH_ASSOC);
//
//
//        } else {
//            echo "Table $moduleName does not exist";
//        }
//    } catch (PDOException $e) {
//        echo "Error: " . $e->getMessage();
//    }
//
//}

echo "<div class='create-form w-100 mx-auto p-4' style='max-width: 700px;'>
    <form action='process.php' method='post' enctype='multipart/form-data'>
        <div class='form-field'>
            <label for='moduleName'>Module</label>";


        foreach ($data as $row) {
        echo "<div>";
        echo "ID: " . $row['id'] . "<br>";
        echo "Name: " . htmlspecialchars($row['moduleName']);
        echo "</div><br>";
        }


echo "<div class='form-field mb-4'>
                <input type='hidden' name='moduleName' value='$moduleName'>
                <input class='btn btn-danger' type='submit' value='Delete' name='delete'>
            </div>

        </div>
    </form>
</div>";


include("../templates/footer.php");




