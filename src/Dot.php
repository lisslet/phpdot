<?php

namespace Dot;
abstract class Dot
{
	static function redirect(string $url)
	{
		if (\headers_sent()) {
			Js::redirect($url);
		} else {
			\header('location:' . $url);
		}
	}

	static function back()
	{
		$referer = $_SERVER['HTTP_REFERER'] ?? '/';
		self::redirect($referer);
	}

	static function turn()
	{

	}
}