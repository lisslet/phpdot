<?php

namespace Dot;

class Css
{
	/**
	 * @param $codes
	 * @return string
	 * @todo upgrade to using like jss
	 */
	static function block($codes): string
	{
		if (\is_array($codes)) {
			$codes = \implode(\PHP_EOL, $codes);
		}

		return '<style>' . $codes . '</style>';
	}

	static function import(string $href)
	{
		return '<link rel="stylesheet" type="text/css" href="' . $href . '">' . \PHP_EOL;
	}
}