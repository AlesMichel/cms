<?php

//use phpCms\Module\module;
include("../../src/DbConnect/connect.php");
include("../../src/Components/ComponentsFetch.php");
include ("../../src/Module/module.php");
$db = \phpCms\DbConnect\connect::getInstance()->getConnection();



$moduleName = $_GET["name"];
if($moduleName){

    //najde module tabulku pomoci jmena
//    $moduleTableName = module::findModuleByName($moduleName, $db);

    //add new entry


}