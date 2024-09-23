<?php

include("../templates/header.php");
include('../../src/DbConnect/connect.php');
$db = \phpCms\DbConnect\connect::getInstance()->getConnection();

// Display success message if available
if (isset($_SESSION['message'])) {
    echo "<div class='alert alert-success'>" . htmlspecialchars($_SESSION['message']) . "</div>";
    unset($_SESSION['message']); // Clear the message after displaying
}

// Display error message if available
if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger'>" . htmlspecialchars($_SESSION['error']) . "</div>";
    unset($_SESSION['error']); // Clear the error message after displaying
}

$sqlSelect = "SELECT * FROM modules";
$stmtSelect = $db->query($sqlSelect);
?>



<table class="table tabled-bordered">
    <tbody>


<?php
while($data = $stmtSelect->fetch(PDO::FETCH_ASSOC)){

    echo "<tr>
            <td>" . $data['module_name'] . "</td>
            <td>" . $data['module_table'] . "</td>
            <td>
                <a class='btn btn-primary' href='view.php?name=" . $data['module_name'] . "'>View</a>
                <a class='btn btn-info' href='edit.php?name=" . $data['module_name'] . "'>Edit</a>
                <a class='btn btn-danger' href='delete.php?name=" . $data['module_name'] . "'>Delete</a>
            </td>
            
        </tr>";
}




?>

    </tbody>
</table>




<?php
include("../templates/footer.php");
?>

