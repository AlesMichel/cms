<?php
include("../../templates/cmsDefaultPage.class.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $componentData = $_SESSION['component_pass_data'];

    $id = $componentData['id'];
    $moduleId = $componentData['module_id'];
    $componentId = $componentData['component_id'];
    $componentName = $componentData['component_name'];
    $componentInstance = $componentData['instance'];
    $componentData = $componentData['component_data'];
    $out = '';


$out .= "<form method='POST' action='process.php'>
        <div class='mb-3'>
        <label for='component_id' class='form-label'>Component ID</label>
        <input type='text' class='form-control' id='component_id' name='component_id' value='$componentId' readonly>
        </div>

        <div class='mb-3'>
        <label for='module_id' class='form-label'>Module ID</label>
        <input type='text' class='form-control' id='module_id' name='module_id' value='$moduleId' readonly>
        </div>

        <div class='mb-3'>
        <label for='component_instance' class='form-label'>Component Instance</label>
        <input type='text' class='form-control' id='component_instance' name='component_instance' value='$componentInstance' readonly>
        </div>
        
        <div class='mb-3'>
        <div class='form-check form-switch'>
        <input name='isRequired' class='form-check-input' type='checkbox' id='flexSwitchCheckChecked' checked>
        <label class='form-check-label' for='flexSwitchCheckChecked'>Komponenta je povinná</label>
         </div>
            
        </div>

        <input type='hidden' name='action' value='editComponent'>
        <input type='hidden' name='component_id' value='$componentId'>
        <input type='hidden' name='id' value='" . htmlspecialchars($id) . "'>

        <button name='update' type='submit' class='btn btn-primary'>Upravit</button>
        </form>";

    
    

    $buildPage = new cmsDefaultPage($out);
    $buildPage->buildLayout();
    
}else{
    echo "No components in current module";
}

