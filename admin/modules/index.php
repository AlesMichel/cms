<?php
include("../templates/header.php");

include('../../src/DbConnect/connect.php');
$db = \phpCms\DbConnect\connect::getInstance()->getConnection();

$sqlSelect = "SELECT * FROM modules";
$stmtSelect = $db->query($sqlSelect);
?>

<table class="table tabled-bordered">
    <tbody>


<?php
while($data = $stmtSelect->fetch(PDO::FETCH_ASSOC)){

    echo "<tr>
            <td>" . $data['moduleName'] . "</td>
            <td>" . $data['moduleTableName'] . "</td>
            <td>
                <a class='btn btn-primary' href='view.php?name=" . $data['moduleName'] . "'>View</a>
                <a class='btn btn-info' href='edit.php?name=" . $data['moduleName'] . "'>Edit</a>
                <a class='btn btn-danger' href='delete.php?name=" . $data['moduleName'] . "'>Delete</a>
            </td>
            
        </tr>";
}




?>

    </tbody>
</table>




<?php
include("../templates/footer.php");
?>

