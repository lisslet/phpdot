<?php

namespace Dot\Html;

use Dot\Css;
use Dot\Js;

class Head
{
    protected $_scripts = [];
    /**
     * @var Styles
     */
    public $styles;

    function __construct()
    {
        $this->styles = new Styles;
    }

    function __toString()
    {
        $html = [];
        $html[] = $this->styles->__toString();

        foreach ($this->_scripts as $src) {
            $html[] = Js::import($src);
        }

        return \implode(PHP_EOL, $html);
    }

    function style(string $url)
    {
        $this->styles->url($url);
        return $this;
    }

    function script(string $src)
    {
        $this->_scripts[] = $src;

        return $this;
    }
}
