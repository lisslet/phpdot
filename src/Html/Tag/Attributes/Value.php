<?php
namespace Dot\Html\Tag\Attributes;

trait Value {
    public $value;

    function value($value){
        $this->value = $value;
        return $this;
    }
}