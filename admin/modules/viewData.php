<?php
include("../../src/DbConnect/connect.php");
include ("../../src/Module/module.php");
include("../templates/cmsDefaultPage.class.php");

// This is the index page for modules
$out = '';
$db = \phpCms\DbConnect\connect::getInstance()->getConnection();
$moduleName = $_GET["module_name"];
$_SESSION["module_name"] = $moduleName;
$getTable = module::findModuleByName($moduleName, $db);

// Print navigation
$out .= cmsDefaultPage::buildNavTabs($moduleName);

if ($moduleName) {
    // Get module ID by its name
    $moduleId = module::getModuleId($moduleName, $db);
    $moduleComponents = module::getModuleData($moduleId, $db);

    // Check if any components are found for this module
    if ($moduleComponents === null || empty($moduleComponents)) {
        $out .= "<p>No components found for this module.</p>";
    } else {
        $out .= "<div class=''><h5>Záznamy pro modul: " . htmlspecialchars($moduleName) . " / id: " . htmlspecialchars($moduleId) . "</h5></div>";

        // Add new data set
        $_SESSION['current_module_id'] = $moduleId;
        $out .= "<form method='POST' action='newEntry.php' class=''>";
        $out .= "<button class='btn btn-primary btn-sm my-3' type='submit'>Přidat nový záznam</button>";
        $out .= "</form>";

        // Loop through each component instance and display the components
        foreach ($moduleComponents as $instance => $components) {


            // View all data
            $out .= "<table class='table table-bordered'>";
            $out .= "<thead>
                     <tr>
                        <th>Název komponenty</th>
                        <th>Hodnota komponenty</th>
                     </tr>
                 </thead>";
            $out .= "<tbody>";

            foreach ($components as $component) {

                // Store component data in the session
                $_SESSION['component_pass_data'][] = [
                    'id' => $component['id'],
                    'module_id' => $moduleId,
                    'component_id' => $component['component_id'],
                    'instance' => $instance,
                    'component_data' => $component['component_data'],
                    'component_name' => $component['component_name']
                ];

                $out .= "<tr>";
                $out .= "<td>" . htmlspecialchars($component['component_name']) . "</td>";
                $out .= "<td>" . htmlspecialchars($component['component_data']) . "</td>";
                $out .= "</tr>";
            }
            // edit data for each instance
            $out .= "<tr>";
            $out .= "<td colspan='2'>";
            $out .= "<form method='POST' action='editEntry.php'>";
            // Hidden input to send the instance ID
            $out .= "<input type='hidden' name='instance_id' value='" . htmlspecialchars($instance) . "'>";
            $out .= "<button class='btn btn-primary btn-sm' type='submit'>Upravit záznam</button>";
            $out .= "</form>";
            $out .= "</td>";
            $out .= "</tr>";


            $out .= "</tbody>";
            $out .= "</table>";

        }


    }
} else {
    $out .= "Module table does not exist";
}

$buildPage = new cmsDefaultPage($out);
$buildPage->buildLayout();
