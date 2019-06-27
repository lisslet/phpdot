<?php

namespace Dot\Html\Tag\Property;

use Dot\Html\DOMTokenList;
use Dot\Html\Tag\TagBase;

class ClassList extends DOMTokenList
{
    protected $_node;

    function __construct(TagBase $node)
    {
        $this->_node = $node;
    }

    function __toString()
    {
        return $this->_node->__toString();
    }

    function toAttributes()
    {
        if ($this->length) {
            return 'class="' . implode(' ', $this->_tokens) . '"';
        }
        return '';
    }
}