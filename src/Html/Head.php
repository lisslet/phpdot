<?php

namespace Dot\Html;

use Dot\Css;
use Dot\Js;

class Head
{
	protected $_styles = [];
	protected $_scripts = [];

	function __toString()
	{
		$html = [];
		foreach ($this->_styles as $href) {
			$html[] = Css::import($href);
		}

		foreach ($this->_scripts as $src) {
			$html[] = Js::import($src);
		}

		return \implode(PHP_EOL, $html);
	}

	function style(string $href)
	{
		$this->_styles[] = $href;

		return $this;
	}

	function script(string $src)
	{
		$this->_scripts[] = $src;

		return $this;
	}
}
