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
    $componentFetch = new ComponentsFetch(null, null, $moduleId);

    //Fetch the module components
    //$moduleComponents = module::getModuleComponents($moduleId, $db);
    $status = $componentFetch->getModuleComponentList();
    $moduleComponents = '';
    if($status['success']){
        $moduleComponents = $status['data'];
    }else{
        $out .= $status['error'];
    }

    if (!empty($moduleComponents)) {
        // Get the latest instance
        //$lastInstance = component::getLastInstance($moduleId, $db);
        $lastInstance = $componentFetch->getHighestInstance()['data'];
        $newInstance = $lastInstance + 1;
        $_SESSION["newInstance"] = $newInstance;
        $_SESSION["current_module_id"] = $moduleId;
        $_SESSION["component_pass_data_insert"] = [];
//        var_dump($moduleComponents);
        $out .= "<form action='components/process.php' method='post'>";
        // Loop through each component and process
//        $componentFetch->getComponentFields($moduleComponents);
        $out .= $componentFetch->getComponentFields($moduleComponents);
        foreach ($moduleComponents as $component) {
            $componentId = $component['component_id'];
            $componentName = $component['component_name'];
            $componentIsRequired = $component['component_required'];
            $componentIsMultlang = $component['component_multlang'];
            // Add current component's data to the session array

            $_SESSION["component_pass_data_insert"][] = [
                'component_id' => $componentId,
                'component_name' => $componentName,
                'component_required' => $componentIsRequired,
                'component_multlang' => $componentIsMultlang
            ];
            // creates the fields
//            $out .= ComponentsFetch::insertComponentData($componentId, $componentName, $db);
        }
        $out .= "<input type='hidden' name='action' value='insertData'>";
        $out .= "<button class='mt-3 btn btn-primary'>Vložit</button>";
        $out .= "</form>";

    } else {
        echo "No components found for this module.";
    }
}


$out .= '<script src="'.ABS_URL.'/modules/components/handleImageUpload.js?v='.time().'" ></script>';
$out .='<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>';

$buildPage = new cmsDefaultPage($out);
$buildPage->buildLayout();