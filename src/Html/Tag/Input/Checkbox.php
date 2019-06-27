<?php

namespace Dot\Html\Tag\Input;

use Dot\Html\Tag\Input;

class Checkbox extends Input
{
    public $type = 'checkbox';
    public $checked;

    function __construct($name = null, $checked = false, $value = null)
    {
        parent::__construct($name, $value);
        if ($checked) {
            $this->checked = true;
        }
    }

    function checked($flag = true)
    {
        $this->checked = $flag;
        return $this;
    }
}