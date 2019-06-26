<?php

namespace Dot;

use Dot\Type\Immutable;

abstract class Js
{
	use Immutable;

	static function import(string $src)
	{
		return '<script src="' . $src . '"></script>' . PHP_EOL;
	}

	static function block($codes)
	{
		if (\is_array($codes)) {
			$codes = implode(PHP_EOL, $codes);
		}

		return '<script>' . \PHP_EOL . $codes . \PHP_EOL . '</script>';
	}


	static function alert($message, $redirect = null)
	{
		if (is_array($message)) {
			$message = \implode(',', $message);
		}

		$message = \addslashes($message);
		echo '<script> window.alert("' . $message . '"); </script>';
		// $redirect && jsRedirect($redirect);
	}

	static function redirect($url)
	{
		echo '<script> window.location.replace("' . $url . '"); </script>';
	}

	static function back()
	{
		echo '<script> window.history.back(); </script>';
	}

	static function log($message)
	{
		echo '<script> console.log("' . $message . '"); </script>';
	}
}