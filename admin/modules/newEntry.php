<?php
include("../../src/DbConnect/connect.php");
include "../../src/Module/module.php";
include("../templates/cmsDefaultPage.class.php");

$out ='';
$db = \phpCms\DbConnect\connect::getInstance()->getConnection();


if($_SERVER["REQUEST_METHOD"] == "POST"){
    $moduleId = $_SESSION['current_module_id'];

   $out .= \phpCms\Module\module::getModuleComponents($moduleId, $db);

}



$buildPage = new cmsDefaultPage($out);
$buildPage->buildLayout();