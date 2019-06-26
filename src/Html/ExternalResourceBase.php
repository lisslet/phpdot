<?php

namespace Dot\Html;

abstract class ExternalResourceBase
{

    /**
     * @var array int
     */
    protected $_levels = [];
    /**
     * @var array string
     */
    protected $_urls = [];

    function url($level_or_url, string $url = null): ExternalResourceBase
    {
        $_urls =& $this->_urls;

        if (gettype($level_or_url) === 'string') {
            $url = $level_or_url;
            $level_or_url = count($_urls);
        }

        if (in_array($url, $_urls)) {
            return $this;
        }

        $this->_levels[] = $level_or_url;
        $_urls[] = $url;

        return $this;
    }

    protected function getUrls(): array
    {
        $_levels =& $this->_levels;
        $_urls =& $this->_urls;

        array_multisort($_levels, SORT_NUMERIC, $_urls);

        return $_urls;
    }
}