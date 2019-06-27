<?php
namespace Dot\Html\Tag\Attributes;

trait Type
{
    public $type;

    function type(string $value)
    {
        $this->type = $value;
        return $this;
    }
}
