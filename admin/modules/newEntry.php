<?php

include("../templates/cmsDefaultPage.class.php");
require_once(__DIR__."/module.php");
require_once(__DIR__."/components/ComponentsFetch.php");
require_once(__DIR__."/../DbConnect/connect.php");
require_once(__DIR__."/../config.php");

use cms\Module\module\module;
use components\Component;
use components\ComponentsFetch\ComponentsFetch;

$out ='';
$db = \cms\DbConnect\connect::getInstance()->getConnection();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $moduleId = $_SESSION['current_module_id'];
    $module = new module(null, null, $moduleId);


    // Fetch the module components
//    $moduleComponents = module::getModuleComponents($moduleId, $db);
    $status = $module->getModuleComponentList();
    $moduleComponents = '';
    if($status['success']){
        $moduleComponents = $status['data'];
    }else{
        $out .= $status['error'];
    }

    if (!empty($moduleComponents)) {
        // Get the latest instance
        $lastInstance = component::getLastInstance($moduleId, $db);
        $newInstance = $lastInstance + 1;
        $_SESSION["newInstance"] = $newInstance;
        $_SESSION["current_module_id"] = $moduleId;
        $_SESSION["component_pass_data_insert"] = [];

        $out .= "<form action='components/process.php' method='post'>";
        // Loop through each component and process
        foreach ($moduleComponents as $component) {
            $componentId = $component['component_id'];
            $componentName = $component['component_name'];
            // Add current component's data to the session array
            $_SESSION["component_pass_data_insert"][] = [$componentId, $componentName];
            // creates the fields
            $out .= ComponentsFetch::insertComponentData($componentId, $componentName, $db);
        }
        $out .= "<input type='hidden' name='action' value='insertData'>";
        $out .= "<button class='mt-3 btn btn-primary'>Vložit</button>";
        $out .= "</form>";

    } else {
        echo "No components found for this module.";
    }
}


$out .= '<script src="'.ABS_URL.'/modules/components/handleImageUpload.js" ></script>';
$out .='<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>';

$buildPage = new cmsDefaultPage($out);
$buildPage->buildLayout();