<?php
include("../../src/DbConnect/connect.php");
include "../../src/Module/module.php";

include("../../src/Components/ComponentsFetch.php");

include("../templates/cmsDefaultPage.class.php");

$out ='';
$db = \phpCms\DbConnect\connect::getInstance()->getConnection();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //get module id and instance id, so I know where to update data
    $moduleId = $_SESSION['current_module_id'];
    $instance = $_POST["instance_id"];
    echo $instance;
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
        $out .= "<input type='hidden' name='action' value='updateData'>";
        $out .= "<input type='hidden' name='instance_id' value='$instance'>";
        $out .= "<button class='mt-3 btn btn-primary'>Vložit</button>";
        $out .= "</form>";

    } else {
        echo "No components found for this module.";
    }
}



$buildPage = new cmsDefaultPage($out);
$buildPage->buildLayout();