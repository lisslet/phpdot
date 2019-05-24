<?php

namespace Dot;

use Dot\Type\Immutable;

abstract class Html {
	static function refresh($url, $delay = 0){
		echo '<meta http-equiv="refresh" content="' . $delay . ';url=\'' . $url . '\'">' . \PHP_EOL;
	}
	static function stylesheet($href, $name = null){
		$name = $name ? ' name="' . $name . '"' : '';
		echo '<link rel="stylesheet" href="' . $href . '"' . $name . '>' . \PHP_EOL;
	}
}