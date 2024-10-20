<?php
namespace components\TextField;
require_once(__DIR__."/Component.php");
use components\Component;

class TextField extends Component
{
    public static function getFields(): string
    {
        return "
        <label class='mt-3' for='textField' class='form-label'>Název komponenty</label>
        <input class='form-control' type='text' id='textField' name='component_name' placeholder='...' required/>
        
        <div class='mt-3'>
            <div class='form-check form-switch'>
            <input type='hidden' name='component_isRequired' value='0'>
            <input name='component_isRequired' class='form-check-input' type='checkbox' id='isRequired' checked value='1'/>
            <label class='form-check-label' for='isRequired'>Komponenta je povinná</label>
            </div>
         </div>
         
        <div class='my-3'>
            <div class='form-check form-switch'>
            <input type='hidden' name='component_isMultilang' value='0'>
            <input name='component_isMultilang' class='form-check-input' type='checkbox' id='isMultilang' checked value='1'/>
            <label class='form-check-label' for='isMultilang'>Komponenta je vícejazyčná</label>
            </div>
        </div>
    ";

    }
    public static function getDataFields($componentId ,$componentName): string{
        return "
        <label for='textField_" .$componentId. "' class='form-label'>" . $componentName ."</label>
        <input class='form-control' type='text' id='textField" . $componentId ."' name='component_" . $componentName ."' placeholder='...' required/>";
    }
    public static function getDataFieldsForEdit($componentId ,$componentName, $componentData): string{
        return "
        <label for='textField_".$componentId."' class='form-label'>" . $componentName ."</label>
        <input class='form-control' type='text' id='textField".$componentId."' value='".$componentData." ' name='component_" . $componentName ."' placeholder='...' required/>";
    }
}