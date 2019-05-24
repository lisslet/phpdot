<?php

namespace Dot\Http;


class Header
{
	static function parse($headerString)
	{
		$headers = [];
		$headerString = array_shift(explode('\r\n\r\n', $headerString));
		$headerString = preg_split('/\r\n/', $headerString);

		foreach ($headerString as $line) {
			if (preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
				$headers[$matches[1]] = trim($matches[2]);
			}
		}

		return $headers;
	}

	static function notFound(){
		header('HTTP/1.0 404 Not Found', true, 404);
	}
}