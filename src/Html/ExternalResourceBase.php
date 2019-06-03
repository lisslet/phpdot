<?php

namespace Dot\Html;

abstract class ExternalResourceBase
{
    protected $_urls = [];

    function url($level_or_url, string $url = null): ExternalResourceBase
    {
        $_urls =& $this->_urls;
        if (gettype($level_or_url) === 'string') {
            $url = $level_or_url;
            $level_or_url = count($_urls);
        }
        $_urls[] = [$level_or_url, $url];
        return $this;
    }

    protected function getUrls(): array
    {
        $_urls =& $this->_urls;
        usort($_urls, __NAMESPACE__ . '\\urlSorter');
        $urls = [];
        foreach ($_urls as $url) {
            $urls[] = $url[1];
        }
        return $urls;
    }
}

function urlSorter(array $a, array $b)
{
    return $a[0] > $b[0];
}
