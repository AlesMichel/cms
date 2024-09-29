<?php

include("Component.php");

class TextField extends Component
{
    protected $placeholder = 'Text...';
    public function __construct($name, $type)
    {
        parent::__construct($name, 'textfield');
    }
    public function render(): string
    {
        return $this->name;
    }
    public static function getFields(): string
    {
        return "
        <label class='mt-3' for='textField' class='form-label'>Název komponenty</label>
        <input class='form-control' type='text' id='textField' name='component_name' placeholder='...' required/>";
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