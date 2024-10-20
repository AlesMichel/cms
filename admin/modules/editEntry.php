<?php

use cms\Module\module\module;
use components\ComponentsFetch\ComponentsFetch;

include("../templates/cmsDefaultPage.class.php");
require_once(__DIR__."/module.php");
require_once(__DIR__."/components/ComponentsFetch.php");
require_once(__DIR__."/../DbConnect/connect.php");
require_once(__DIR__."/../config.php");

$out ='';
$db = \cms\DbConnect\connect::getInstance()->getConnection();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //get module id and instance id, so I know where to update data
    $moduleId = $_SESSION['current_module_id'];
    $instance = $_POST["instance_id"];
    // Fetch the module components
    $moduleComponents = module::getModuleDataForInstance($moduleId, $instance, $db);
//    var_dump($moduleComponents);
    if (!empty($moduleComponents)) {

        $_SESSION['current_module_id'] = $moduleId;
        $_SESSION["current_instance"] = $instance;
        $_SESSION["component_pass_data_update"] = [];

        $out .= "<form action='components/process.php' method='post'>";
        // Loop through each component and process
        foreach ($moduleComponents as $component) {
            $componentId = $component['component_id'];
            $componentName = $component['component_name'];
            $componentData = $component['component_data'];
            // Add current component's data to the session array
            $_SESSION["component_pass_data_update"][] = [$componentId, $componentName];
            // creates the fields
            $out .= ComponentsFetch::editComponentData($componentId, $componentName, $componentData , $db);
        }

        $out .= "<input type='hidden' name='instance_id' value='$instance'>";
        $out .= "<button name='action' value='updateData' class='mt-3 btn btn-primary'>Vložit</button>";
        $out .= "<button name='action' value='deleteData' class='mt-3 btn btn-danger ms-3' >Smazat záznam</button>";
        $out .= "</form>";

    } else {
        echo "No components found for this module.";
    }
}

$out .='<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>';


$out .= '<script src="'.ABS_URL.'/modules/components/handleImageUpload.js?v='.time().'" defer></script>';
$buildPage = new cmsDefaultPage($out);
$buildPage->buildLayout();