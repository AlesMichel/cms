<?php

use components\ComponentsFetch\ComponentsFetch;

include("../../templates/cmsDefaultPage.class.php");
require_once(__DIR__."/../module.php");
require_once(__DIR__."/ComponentsFetch.php");
require_once(__DIR__."/../../DbConnect/connect.php");
require_once(__DIR__."/../../config.php");

//deletes a component based on name
$db = \cms\DbConnect\connect::getInstance()->getConnection();

$out = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $componentPassData = $_SESSION['component_pass_data'];
    $componentId = $componentPassData['component_id'];
    $componentName = $componentPassData['component_name'];

    $fetchComponentTable = ComponentsFetch::printComponentTable($componentId, $componentName, $db);
    if($fetchComponentTable){
        $_SESSION['component_pass_data'] = $componentPassData;
        $out .= $fetchComponentTable;
        $out .= '<form method="post" action="process.php">
                    <input type="hidden" name="action" value="delete">
                    <button class="btn btn-danger btn" type="submit">Smazat</button>
                 </form>';
    }else{
        $out .= "Component not found";
    }
}

$buildPage = new cmsDefaultPage($out);
$buildPage->buildLayout();