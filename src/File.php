<?php
namespace Dot;
abstract class File
{
	static function name(string $file)
	{
		return \pathinfo($file, \PATHINFO_FILENAME);
	}

	static function write(string $file, string $contents)
	{
		try {
			$fp = \fopen($file, 'w');
		} catch (Exception $e) {
			return false;
		}

		if (!$fp) {
			return false;
		}

		if (\is_array($contents)) {
			$contents = \implode(\PHP_EOL, $contents);
		}

		if (\flock($fp, \LOCK_EX)) {
			\fputs($fp, $contents);
		}

		\fclose($fp);

		return true;
	}

	static function read(string $file)
	{
		try {
			$fp = \fopen($file, 'r');
		} catch (Exception $e) {
			return false;
		}

		if (!$fp) {
			return false;
		}

		$contents = null;
		if (\flock($fp, LOCK_EX)) {
			$contents = \fread($fp, \filesize($file));
		}

		\fclose($fp);

		return $contents;
	}

}