<?php

include("../templates/cmsDefaultPage.class.php");
include('../../src/DbConnect/connect.php');
$db = \phpCms\DbConnect\connect::getInstance()->getConnection();


$out = '';
$sqlSelect = "SELECT * FROM modules";
$stmtSelect = $db->query($sqlSelect);



$out .= '<table class="table tabled-bordered">
            <thead>
                <tr class="fw-bold">
                    <td>Název modulu</td>
                    <td>Název tabulky</td>
                    <td>Akce</td>
                </tr>
            </thead>
        <tbody>';
while($data = $stmtSelect->fetch(PDO::FETCH_ASSOC)){

    $out .= "<tr>
            <td>" . $data['module_name'] . "</td>
            <td>" . $data['module_table'] . "</td>
            <td>
                <a class='btn btn-primary' href='viewData.php?name=" . $data['module_name'] . "'>View</a>
           
                <a class='btn btn-danger' href='delete.php?name=" . $data['module_name'] . "'>Delete</a>
            </td>
            
        </tr>";
}






   $out .= '</tbody></table>';



$buildPage = new cmsDefaultPage($out);
$buildPage->buildLayout();



