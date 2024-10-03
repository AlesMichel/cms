<?php



class Image extends Component
{
    protected $placeholder = 'Text...';
    public function __construct($name, $type)
    {
        parent::__construct($name, 'image');
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
        <input class='form-control' type='file' id='textField" . $componentId ."' name='component_" . $componentName ."' placeholder='...' required/>";
    }
    public static function getDataFieldsForEdit($componentId ,$componentName, $componentData): string{
        $out = '';
        $out .= "
        <label for='textField_".$componentId."' class='form-label'>" . $componentName ."</label>";

        if($componentData){

                $out .= "<img id='preview_" . $componentId . "' src='" . $componentData . "' alt='img-field' class='img-thumbnail' />";

        }else{
            $out .= ' / Záznam zatím nemá data';
        }

        $out .= "<input onchange='handleImageUpload()' type='file' name='component_" . $componentName ."'  class='form-control' id='image".$componentId."' value='" . $componentData ."'/>";

        return $out;
    }
}