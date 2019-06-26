<?php

namespace Dot\Html;

use Dot\Css;
use Dot\Js;

class Head
{
    /**
     * @var boolean
     */
    protected $_flushed;

    /**
     * @var Styles
     */
    public $styles;

    /**
     * @var Scripts
     */
    public $scripts;


    function __construct()
    {
        $this->styles = new Styles;
        $this->scripts = new Scripts;
    }

    function __toString()
    {
        $this->_flushed = true;
        $html = [];

        $html[] = $this->styles->__toString();
        $html[] = $this->scripts->__toString();

        return \implode(PHP_EOL, $html);
    }

    function style(string $url)
    {
        $this->styles->url($url);
        return $this;
    }

    function script(string $url)
    {
        if ($this->_flushed) {
            echo Js::import($url);
        } else {
            $this->scripts->url($url);
        }
        return $this;
    }
}