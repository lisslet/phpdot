<?php

namespace Dot\Html\Tag;

class Textarea extends ControlBase
{
    public $tagName = 'textarea';

    function toString()
    {
        $this->innerHTML = $this->value;
        $this->value = null;
        return parent::toString();
    }
}