<?php
include("../../templates/cmsDefaultPage.class.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $componentData = $_SESSION['component_pass_data'];

    $id = $componentData['id'];
    $moduleId = $componentData['module_id'];
    $componentId = $componentData['component_id'];
    $componentName = $componentData['component_name'];
    $componentInstance = $componentData['component_instance'];
    $componentIsMultlang = $componentData['component_multlang'];
    $componentIsRequired = $componentData['component_required'];
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
        
        <div class='mt-3'>
            <div class='form-check form-switch'>
            <input type='hidden' name='component_isRequired' value='0'>
            <input name='component_isRequired' class='form-check-input' type='checkbox' id='isRequired' value='1'  " . ($componentIsRequired == 1 ? 'checked' : '') . " />
            <label class='form-check-label' for='isRequired'>Komponenta je povinná</label>
            </div>
         </div>
         
        <div class='my-3'>
            <div class='form-check form-switch'>
            <input type='hidden' name='component_isMultilang' value='0'>
            <input name='component_isMultilang' class='form-check-input' type='checkbox' id='isMultilang' value='1'  " . ($componentIsMultlang == 1 ? 'checked' : '') . " />
            <label class='form-check-label' for='isMultilang'>Komponenta je vícejazyčná</label>
            </div>
       </div>
            
  

        <input type='hidden' name='action' value='editComponent'>
        <input type='hidden' name='component_id' value='$componentId'>
        <input type='hidden' name='id' value='" . htmlspecialchars($id) . "'>

        <button name='editComponent' type='submit' class='btn btn-primary'>Upravit</button>
        </form>";

    $buildPage = new cmsDefaultPage($out);
    $buildPage->buildLayout();
    
}else{
    echo "No components in current module";
}

