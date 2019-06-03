<?php

namespace Dot\Html;
use Dot\Css;

class Styles extends ExternalResourceBase
{
    function __toString()
    {
        $urls = $this->getUrls();
        $html = [];
        foreach ($urls as $url) {
            $html[] = Css::import($url);
        }
        return implode(PHP_EOL, $html);
    }
}