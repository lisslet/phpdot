<?php

namespace Dot\Html;

use Dot\Js;

class Scripts extends ExternalResourceBase
{
    function __toString()
    {
        $urls = $this->getUrls();
        $html = [];
        foreach ($urls as $url) {
            $html[] = Js::import($url);
        }
        return implode(PHP_EOL, $html);
    }
}