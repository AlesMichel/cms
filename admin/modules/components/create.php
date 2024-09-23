<?php

require_once __DIR__ . '/../../../vendor/autoload.php';
use phpCms\Module\module;
use phpCms\Components\Component;
use phpCms\DbConnect\connect;


$db = connect::getInstance()->getConnection();

if (isset($_POST['add_component']) && isset($_POST['component_id']) && isset($_GET['current_module'])) {
    $currentModule = $_GET['current_module']; // Get current module
    $componentId = $_POST['component_id'];  // Get the selected component's ID
    //now we have components id and we wil fetch its name by id

    $fetchComponent = Component::getComponentById($componentId, $db);
    echo "
            <table class='table'>
            <thead>
            <tr>
              <th scope='col'>component_id</th>
              <th scope='col'>component_name</th>
              <th scope='col'>current module</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope='row'>  " . $fetchComponent['id'] . " </th>
              <td>  " . $fetchComponent['name'] . " </td>
              <td>  " . $currentModule . " </td>
            </tr>
            <tr>
          </tbody>
        </table>
                        
            ";
    echo "<div class='create-form w-100 mx-auto p-4' style='max-width: 700px;'>
    <form action='process.php' method='post' enctype='multipart/form-data'>
        <div class='form-field'>
            <label for='componentName'>Component Name</label>
            <input class='form-control' placeholder='Enter component name' type='text' name='componentName' id='componentName' required>
            
            <input class='form-control d-none' value='" . htmlspecialchars($componentId) . "'  type='text' name='componentId' id='componentId '>
            <input class='form-control d-none' value='" . htmlspecialchars($currentModule) . "'    type='text' name='moduleName' id='moduleName'>
          

            <div class='form-field mb-4'>
                <input class='btn btn-primary' type='submit' value='Submit' name='create'>
            </div>

        </div>
    </form>
</div>";



}