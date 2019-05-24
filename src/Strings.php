<?php

namespace Dot;

class Strings
{
	const az = 'abcdefghijklmnopqrstuvwxyz';
	const AZ = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	const aZ = self::az . self::AZ;
	const NUMBERS = '0123456789';
	const ID_BEGIN = self::az . '_';
	const ID_NEXT = self::aZ . self::NUMBERS . '_';

	static function starts(string $haystack, $needle): bool
	{
		return $haystack[0] === $needle[0] ?
			\strncmp($haystack, $needle, strlen($needle)) === 0 :
			null;
	}

	static function ends(string $haystack, $needle): bool
	{
		return \strcmp(\substr($haystack, \strlen($haystack) - \strlen($needle)), $needle) === 0;
	}

	/**
	 * @param $strings
	 * @param string $head
	 * @param string|null $foot
	 * @param string $glue
	 * @return string
	 */
	static function wrap($strings, string $head, string $foot = null, string $glue = '')
	{
		if (!$foot) {
			$foot = $head;
		}

		return \is_array($strings) ?
			$head . \implode($foot . $glue . $head, $strings) . $foot :
			$head . $strings . $foot;
	}

	static function toSlug(string $value)
	{
		return strtolower($value);
	}
}