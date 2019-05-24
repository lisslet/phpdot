<?php

namespace Dot\Dev;

class Console
{
	static function log()
	{
		$args = func_get_args();
		$args = array_map(function ($arg) {
			return php2js($arg);
		}, $args);
		$args = implode(', ', $args);
		echo '<script>',
			'console.log(' . $args . ');',
			'</script>' . PHP_EOL;
	}
}

function php2js($target)
{
	if (gettype($target) === 'object') {
		if (method_exists($target, '__toString')) {
			return php2js($target->__toString());
		}

		$className = get_class($target);
		$className = str_replace('\\', '_', $className);

		$properties = [];

		foreach ($target as $key => $value) {
			$properties[] = "this['{$key}'] = " . php2js($value);
		}
		$properties = implode(';' . PHP_EOL, $properties);

		$jsClass = 'new (function ' . $className . '(){' . $properties . '})';

		return $jsClass;
	}

	return 'JSON.parse("' . addslashes(json_encode($target)) . '")';
}