<?php

namespace Dot\Html\Tag\Attributes;

trait Name
{
    public $name;

    function name(string $value)
    {
        $this->name = $value;
        return $this;
    }
}