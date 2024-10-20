<?php
namespace components\Position;
require_once(__DIR__."/Component.php");
use components\Component;

class Position extends Component
{
    public static function getFields(): string
    {
        return "
        <label class='mt-3' for='textField' class='form-label'>Název komponenty</label>
        <input class='form-control' type='text' id='textField' name='component_name' placeholder='...' required/>";
    }
    public static function getDataFields($componentName): string{
        return "
        <label for='position_" .$componentName. "' class='form-label'>" . $componentName ."</label>
        <input class='form-control' type='text' id='position" . $componentName ."' name='component_" . $componentName ."' placeholder='...' required/>";
    }
    public static function getDataFieldsForEdit($componentName, $componentData): string{
        return "
        <label for='position_".$componentName."' class='form-label'>" . $componentName ."</label>
        <input class='form-control' type='text' id='textField".$componentName."' value='".$componentData." ' name='component_" . $componentName ."' placeholder='...' required/>";
    }
}