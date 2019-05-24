<?php

namespace Dot;

/**
 * number starts with zero
 * @param $value
 * @param int $length
 * @return string
 * @todo need to bench with str_pad
 */
function zerofill($value, $length = 2)
{
	$diffLength = $length - \strlen($value);

	return $diffLength > 0 ?
		\str_repeat('0', $diffLength) . $value :
		$value;
}

function startWith(string $haystack, $needle) {
	return \strncmp($haystack, $needle, strlen($needle)) === 0;
}