<?php

namespace Dot\Html\Tag;

use Dot\Dot;
use Dot\Html\Tag\Property\ClassList;
use phpbrowscap\Exception;
use stdClass;

/**
 * @property ClassList classList
 * @property stdClass dataset
 * @property string tagName
 * @property array _attrs
 */
abstract class TagBase
{
    protected $_selfCloser = false;

    /**
     * [id]
     * @var string
     */
    public $id;

    /**
     * .innerHTML
     * @var string
     */
    public $innerHTML;

    function __get($name)
    {
        switch ($name) {
            case 'tagName':
                $this->tagName = strtolower(get_class($this));
                return $this->tagName;
            case 'classList':
                $this->classList = new ClassList($this);
                return $this->classList;

            case 'dataset':
                $this->dataset = new stdClass;
                return null;

            case '_attrs':
                $this->_attrs = $this->_getAttributes($this);
                return $this->_attrs;

        }
        user_error("{$name} is empty property");
        return null;
    }

    function __toString()
    {
        try {
            return $this->toString();
        } catch (Exception $exception) {
            return '';
        }
    }

    //abstract function toString(): string;

    function toString()
    {
        $attrString = [];
        foreach ($this->_attrs as $attrName) {
            $attrValue = $this->{$attrName} ?? '';
            if ($attrValue) {

                $attrString[] = $attrValue === true ?
                    $attrName :
                    $attrName . '="' . $attrValue . '"';
            }
        }

        if ($this->classList->length) {
            $attrString[] = 'class="' . implode(' ', $this->classList->values()) . '"';
        }

        $attrString = $attrString ?
            ' ' . implode(' ', $attrString) :
            '';

        $html = [];

        $tagName = $this->tagName;
        $open = '<' . $tagName . $attrString;
        $open .= '>';

        $html[] = $open;

        if ($this->innerHTML) {
            $html[] = $this->innerHTML;
        }

        if (!$this->_selfCloser) {
            $close = '</' . $tagName . '>';
            $html[] = $close;
        }
        $html = implode('', $html);
        return $html;
    }

    function id(string $value)
    {
        $this->id = $value;
        return $this;
    }

    protected function _getAttributes($tag): array
    {
        $traits = Dot::getTraits($tag);
        $attrs = $this->_attributes ?? [];
        foreach ($traits as $className) {
            $ns = explode('\\', $className);
            list($type, $name) = array_splice($ns, count($ns) - 2, 2);

            if ($type === 'Attributes') {
                $attrs[] = strtolower($name);
            }
        }
        return $attrs;
    }
}
