<?php

include("../../src/DbConnect/connect.php");
include "../../src/Module/module.php";
include("../templates/cmsDefaultPage.class.php");

//this is index page for modules

$out = '';
$db = \phpCms\DbConnect\connect::getInstance()->getConnection();
$moduleName = $_GET["name"];
$_SESSION["current_module_id"] = \phpCms\Module\module::getModuleId($moduleName, $db);
$getTable = \phpCms\Module\module::findModuleByName($moduleName, $db);

//print navigaton
$out .= cmsDefaultPage::buildNavTabs($moduleName);

if ($moduleName) {
    //get module id by its name
    $moduleId = \phpCms\Module\module::getModuleId($moduleName, $db);
    $moduleComponents = \phpCms\Module\module::getModuleData($moduleId, $db);


    // Check if any components are found for this module
    if (empty($moduleComponents)) {
        $out .= "<p>No components found for this module.</p>";
    } else {
        $out .= "<div class=''><h5>Components for Module ID: " . htmlspecialchars($moduleId) . " /  " . htmlspecialchars($moduleName) . " </h5></div>";

        // add new data set

        $_SESSION['current_module_id'] = $moduleId;
        $out .= "<form method='POST' action='./components/create.php'>";

        $out .= "<button class='btn btn-primary btn-sm my-3' type='submit'>Přidat komponentu</button>";
        $out .= "</form>";

        // view all data

        $out .= "<table class='table table-bordered'>";
        $out .= "<thead>
        <tr>
            <th>Nazev komponenty</th>
            <th>Hodnota komponenty</th>
            <th>Akce</th>
        </tr>
      </thead>";
        $out .= "<tbody>";

        // Loop through each component instance and display the components
        foreach ($moduleComponents as $instance => $components) {
            foreach ($components as $component) {
                $out .= "<tr>";
                $out .= "<td>" . htmlspecialchars($component['component_name']) . "</td>";
                $out .= "<td>" . htmlspecialchars($component['component_data']) . "</td>";

                // Store component data in the session
                $_SESSION['component_pass_data'] = [
                    'id' => $component['id'],
                    'module_id' => $moduleId,
                    'component_id' => $component['component_id'],
                    'instance' => $instance,
                    'component_data' => $component['component_data'],
                    'component_name' => $component['component_name']
                ];
                // Form with hidden fields to pass component data
                $out .= "<td>";
                $out .= "<form method='POST' action='./components/edit.php'>";

                $out .= "<button class='btn btn-primary btn-sm' type='submit'>Upravit</button>";
                $out .= "</form>";
                $out .= "</td>";
                $out .= "</tr>";
            }
        }

        $out .= "</tbody>";
        $out .= "</table>";

    }

} else {
    $out .= "Module table does not exist";
}

$buildPage = new cmsDefaultPage($out);
$buildPage->buildLayout();