<?php
include("../../src/DbConnect/connect.php");
include "../../src/Module/module.php";

include("../../src/Components/ComponentsFetch.php");

include("../templates/cmsDefaultPage.class.php");

$out ='';
$db = \phpCms\DbConnect\connect::getInstance()->getConnection();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $moduleId = $_SESSION['current_module_id'];

    // Fetch the module components
    $moduleComponents = module::getModuleComponents($moduleId, $db);
    var_dump($moduleComponents);
    echo $moduleId;
    if (!empty($moduleComponents)) {
        // Get the latest instance
        $lastInstance = component::getLastInstance($moduleId, $db);
        $newInstance = $lastInstance + 1;
        echo "new instance" . $newInstance;
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



$buildPage = new cmsDefaultPage($out);
$buildPage->buildLayout();