<?php

namespace Dot\Html\Tag;

use Dot\Html\Tag\Attributes\Name;
use Dot\Html\Tag\Attributes\Value;

class ControlBase extends TagBase
{
    use Name;
    use Value;

    protected $_attributes = [
        'disabled',
        'required',
        'min',
        'max',
        'minLength',
        'maxLength',
        'pattern'
    ];

    /**
     * @var bool
     */
    public $disabled;

    /**
     * @var bool
     */
    public $required;

    /**
     * @var string
     */
    public $placeholder;

    public $min;

    public $max;

    public $minLength;

    public $maxLength;

    public $pattern;

    /**
     * @var string
     */
    public $label;

    function __construct(string $name = null, $value = null)
    {
        if ($name) {
            $this->name = $name;
        }

        if ($value) {
            $this->value = $value;
        }
    }

    function disabled(bool $flag = true)
    {
        $this->disabled = $flag;
        return $this;
    }

    function required(bool $flag = true)
    {
        $this->required = $flag;
        return $this;
    }

    function placeholder(string $value)
    {
        $this->placeholder = $value;
        return $this;
    }


    function min(int $value)
    {
        $this->min = $value;
        return $this;
    }

    function max(int $value)
    {
        $this->max = $value;
        return $this;
    }

    function minLength(int $value)
    {
        $this->minLength = $value;
        return $this;
    }

    function maxLength(int $value)
    {
        $this->maxLength = $value;
        return $this;
    }

    function pattern(string $value)
    {
        $this->value = $value;
        return $this;
    }

    function label(string $value)
    {
        $this->label = $value;
        return $this;
    }

}