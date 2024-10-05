<?php
namespace components\Image;
require_once(__DIR__."/Component.php");
use components\Component;

class Image extends Component
{
    protected $placeholder = 'Text...';

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

    //ai
    public static function viewImage($data): string {
        // Check if the provided data is a valid base64 image string
        if (strpos($data, 'data:image') === 0) {
            // The data is already a base64 encoded image
            $imageSrc = $data;
        } elseif (is_string($data) && !empty($data)) {
            // The data could be a LONGBLOB stored as binary, convert to base64
            // Assuming $data is raw binary data from your LONGBLOB column
            $imageSrc = 'data:image/png;base64,' . base64_encode($data);
        } elseif (file_exists($data)) {
            // The data is a file path (on the server)
            $imageSrc = $data; // Ensure the file path is accessible from the web (e.g., public folder)
        } elseif (filter_var($data, FILTER_VALIDATE_URL)) {
            // The data is a valid URL
            $imageSrc = $data;
        } else {
            // Invalid image data
            return '<p>Invalid image data.</p>';
        }

        // Return the HTML to display the image
        return '<img src="' . htmlspecialchars($imageSrc) . '" style="max-width:200px;" alt="Image" class="img-thumbnail" />';
    }


    ///ai
    public static function getDataFieldsForEdit($componentId ,$componentName, $componentData): string{
        $out = '';
        $out .= "
        <label for='textField_".$componentId."' class='form-label'>" . $componentName ."</label>";

        if($componentData){

//                $out .= "<img id='preview_" . $componentId . "' src='" . $componentData . "' alt='img-field' class='img-thumbnail' />";
            self::viewImage($componentData);


        }else{
            $out .= ' / Záznam zatím nemá data';
        }
        $out .= '<img id="imagePreview' . $componentName . '" src="" class="img-thumbnail d-none" />';
        $out .= '<button class="btn btn-primary opacity-0" id="cropBtn' . $componentName .'">Použít</button>';

        //hidden input for passing data
        $out .= "<input type='hidden' id='dataPassImg" . $componentName . " ' value='" . $componentData . "' name='component_" . $componentName ."' />";

        $out .= "<input onchange='handleImageUpload(this,\"" . $componentName . "\")' type='file' name='input_" . $componentName ."' class='form-control mt-3' id='image".$componentName."' accept='image/png, image/gif, image/jpeg image/webp'/>";



        return $out;
    }
}