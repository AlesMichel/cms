<?php

namespace phpCms\Components;
//common components class
abstract class Component
{
    protected $name;
    protected $type;
    public function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;

    }
    abstract public function render();


}