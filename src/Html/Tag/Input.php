<?php

namespace Dot\Html\Tag;

use Dot\Html\Tag\Attributes\Name;
use Dot\Html\Tag\Attributes\Value;
use Dot\Html\Tag\Attributes\Type;

class Input extends ControlBase
{
    use Type;

    protected $_selfCloser = true;

    public $tagName = 'input';
}