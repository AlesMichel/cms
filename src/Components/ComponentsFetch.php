<?php
namespace phpCms\Components;

use PDO;
use PDOException;

class ComponentsFetch {

    public static function fetchAllComponents($db) {
        try {
            $sql = 'SELECT * FROM components';
            $stmt = $db->prepare($sql);
            $stmt->execute(); // Execute the query
            $fetchAllComponents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
        return $fetchAllComponents;
    }

    public static function renderComponents($db): string {
        // Fetch all components from the database
        $data = self::fetchAllComponents($db);
        if ($data === null) {
            return '<p>No components found.</p>'; // Handle the case where no data is returned
        }

        $out = '';

        // Start the HTML for the select box
        $out .= '<label for="component">Choose a component:</label>
                 <select name="component_id" id="component">';

        // Loop through each component and create an option element
        foreach ($data as $component) {
            $out .= '<option value="' . htmlspecialchars($component['id']) . '">' .
                htmlspecialchars($component['name']) .
                '</option>';
        }

        // Close the select box
        $out .= '</select>';

        return $out;
    }
}
