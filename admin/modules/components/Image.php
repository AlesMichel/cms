<?php
namespace components\Image;
require_once(__DIR__."/Component.php");
use components\Component;

class Image extends Component
{
    protected $placeholder = 'Text...';


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
    public static function viewImage($filename): string {
        // Create the relative URL for the image
        $src = '/cms/admin/uploads/' . basename($filename);

        // Return the HTML to display the image
        return '<img src="' . htmlspecialchars($src) . '" style="max-width:100px; min-width:100px;" alt="Image" class="img-thumbnail" />';
    }
    public static function deleteFiles(array $files): void
    {
        foreach($files as $file){
            echo $file;
            if(file_exists($file)){
                unlink($file);
            }
        }
    }

    public function uploadImage($src): array
    {
        $result = [
            'success' => false,
            'data' => null,
            'error' => null,
        ];
        //uploads image to uploads
        //first as ChatGPT said explode the tag
        if($src){
            $imageData = explode(',', $src)[1];
            //nice got the path
            //now decode the image
            $decodedImage = base64_decode($imageData);
            $webpFileName = 'C:/xampp/htdocs/cms/admin/uploads/image_' . time() . '.webp';
//            $webpFileName = ABS_URL. 'C:/xampp/htdocs/cms/admin/uploads/image_' . time() . '.webp';
            $image = imagecreatefromstring($decodedImage);

            // Convert the image to WebP and save it
            if (imagewebp($image, $webpFileName)) {
                echo "Image successfully converted to WebP and saved as $webpFileName";
                $result['data'] = $webpFileName;
                $result['success'] = true;
            } else {
                $result['error'] = 'Failed to convert and save the image.';
            }

            // Free up memory
            imagedestroy($image);

        }else{
            $result['success'] = false;
            $result['error'] = "Haven't received the image data";
        }

        return $result;
    }

    ///ai
    public static function getDataFieldsForEdit($componentId ,$componentName, $componentData): string{
        $out = '';
        $out .= "
        <label for='textField_".$componentId."' class='form-label'>" . $componentName ."</label>";

        if ($componentData) {
            // Generate the HTML to view the existing image
            $out .= self::viewImage($componentData); // Assuming viewImage returns the HTML for the image
        } else {
            $out .= ' / Záznam zatím nemá data'; // Message if there's no data
        }

        $out .= '<img id="imagePreview' . $componentName . '" src="" class="img-thumbnail d-none" />';
        $out .= '<button class="btn btn-primary mt-3 opacity-0" id="cropBtn' . $componentName .'">Použít</button>';
        $out .= "<input class='d-none' type='text' id='dataPassImg" . $componentName . "' value='" . $componentData . "' name='component_" . $componentName ."' />";
        $out .= "<input onchange='handleImageUpload(this,\"".$componentName."\")' type='file' name='input_" . $componentName ."' class='form-control mt-3' id='image".$componentName."' accept='image/png, image/gif, image/jpeg image/webp'/>";




        return $out;
    }
}