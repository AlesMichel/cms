<?php

namespace phpCms\Components;

class TextField extends Component
{
    protected $placeholder;
    public function __construct($name, $type)
    {
        parent::__construct($name, 'textfield');
        $this->placeholder = $placeholder;
    }

    public function render(): string
    {
        return "<input type='text' name='$this->name' placeholder='$this->placeholder' />";
    }
}