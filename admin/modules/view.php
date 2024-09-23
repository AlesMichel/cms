<?php
//connect to db
include("../../src/DbConnect/connect.php");
include "../../src/Module/module.php";
include("../templates/header.php");


$db = \phpCms\DbConnect\connect::getInstance()->getConnection();
$moduleName = $_GET["name"];
$getTable = \phpCms\Module\module::findModuleByName($moduleName, $db);
if ($moduleName) {
    //get module id by its name
    $moduleId = \phpCms\Module\module::getModuleId($moduleName, $db);
    // Fetch module component data using the static method
    $moduleComponents = \phpCms\Module\module::getModuleData($moduleId, $db);

// Check if any components are found for this module
    if ($moduleComponents === null || empty($moduleComponents)) {
        echo "<p>No components found for this module.</p>";
    } else {
        echo "<h2>Components for Module ID: " . htmlspecialchars($moduleId) . " /  " . htmlspecialchars($moduleName) . " </h2>";

// Start the HTML table
        echo "<table class='table table-bordered'>";
        echo "<thead>
        <tr>
            <th>Nazev komponenty</th>
            <th>Hodnota komponenty</th>
            <th>Akce</th>
        </tr>
      </thead>";
        echo "<tbody>";

// Loop through each component instance and display the components
        foreach ($moduleComponents as $instance => $components) {
            foreach ($components as $component) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($component['component_name']) . "</td>";
                echo "<td>" . htmlspecialchars($component['component_data']) . "</td>";

                // Form and button inside a table cell
                echo "<td>";
                echo "<form method='GET' action='components/edit.php' class='d-inline'>";
                echo "<input type='hidden' name='id' value='" . htmlspecialchars($component['id']) . "'>";
                echo "<input type='hidden' name='module_id' value='" . htmlspecialchars($moduleId) . "'>";
                echo "<input type='hidden' name='component_id' value='" . htmlspecialchars($component['component_id']) . "'>";
                echo "<input type='hidden' name='instance' value='" . htmlspecialchars($instance) . "'>";
                echo "<input type='hidden' name='component_data' value='" . htmlspecialchars($component['component_data']) . "'>";
                echo "<button class='btn btn-primary btn-sm' type='submit'>Upravit</button>";
                echo "</form>";
                echo "</td>";

                echo "</tr>";
            }
        }

        echo "</tbody>";
        echo "</table>";
    }

}else{
    echo "Module table does not exist";
}