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
}