<?php

require_once __DIR__ . '/admin/config.php';
require_once ABS_PATH . "/config.php";
require_once("./client/buildPage.php");


require_once(ABS_PATH . '/DbConnect/connect.php');
require_once(ABS_PATH . '/modules/components/Component.php');
require_once(ABS_PATH . '/modules/components/Image.php');
require_once(ABS_PATH . '/modules/module.php');

use cms\Module\module\module;
use components\Component;
use components\Image\Image;

// This is the index page for modules
$out = '';
$db = \cms\DbConnect\connect::getInstance()->getConnection();
$moduleName = 'modul1';
$module = new module($moduleName);
$moduleId = $module->getID();

// Print navigation


if ($moduleName) {
    // Get module ID by its name
    $moduleComponents = $module->getModuleData();
    $highestInstance = component::getLastInstance($moduleId, $db);

    // Check if any components are found for this module
    if (empty($moduleComponents)) {
        $out .= "<p>No components found for this module.</p>";
    } elseif ($highestInstance === 0) {
        $out .= "<p>Tento modul nema zadne záznamy</p>";

    } else {


        $out .= "<div class=''><h5>Záznamy pro modul: " . htmlspecialchars($moduleName) . " / id: " . htmlspecialchars($moduleId) . "</h5></div>";


        // Loop through each component instance and display the components
        foreach ($moduleComponents as $instance => $components) {

            if ($instance > 0) {
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
                        'instance' => $component['component_instance'],
                        'component_data' => $component['component_data'],
                        'component_name' => $component['component_name']
                    ];
                    // Start the table row
                    if ($component['component_instance'] > 0) {
                        $out .= "<tr>";
                        // Check if the component ID indicates an image component (e.g., ID 2)
                        if ($component['component_id'] == 2) {
                            $out .= "<td>" . htmlspecialchars($component['component_name']) . "</td>";
                            $out .= "<td>" . Image::viewImage($component['component_data']) . "</td>"; // Display image
                        } else {
                            // Otherwise, display the component name and data as plain text
                            $out .= "<td>" . htmlspecialchars($component['component_name']) . "</td>";
                            $out .= "<td>" . htmlspecialchars($component['component_data']) . "</td>";
                        }

                        // Close the table row
                        $out .= "</tr>";
                    }
                }


            }
            $out .= "</tbody>";
            $out .= "</table>";

        }


    }
} else {
    $out .= "Module table does not exist";
}

$buildPage = new buildPage($out);
$buildPage->buildLayout();
