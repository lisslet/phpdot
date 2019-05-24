<?php

namespace Dot;

function hideRoot(string $path)
{
	return \str_replace(
		[
			dirname($_SERVER['SCRIPT_FILENAME']),
			$_SERVER['DOCUMENT_ROOT']
		],
		['', ''],
		$path);
}

class Dev
{
	static $errored;

	static function mode(array $options = [])
	{
		$errorReport = $options['errorReport'] ?? E_ALL;

		self::errors($errorReport);

		$excludes = $options['excludes'] ?? null;
		if ($excludes) {
			if (in_array(basename($_SERVER['SCRIPT_NAME']), $excludes)) {
				return;
			}
		}

		register_shutdown_function(function () {
			echo Css::block(File::read(__DIR__ . '/assets/dev-beautifier.css'));
			echo Js::block(File::read(__DIR__ . '/assets/dev-beautifier.js'));
		});
	}

	static function errors($level = \E_ALL)
	{
		\error_reporting($level);
		\ini_set('display_errors', true);
	}

	static function stop(string $message, string $file = null, int $line = null)
	{
		if ($file) {
			$message .= ' in <b>' . hideRoot($file) . '</b>';
		}

		if ($line) {
			$message .= ' on line <b>' . $line . '</b>';
		}

		die('<div><b>Stop:</b>:' . $message . '</div>');
	}
}

function getContents(string $method, array &$args = [])
{
	\ob_start();
	\call_user_func_array($method, $args);
	$contents = \ob_get_contents();
	\ob_clean();

	return $contents;
}

function export($value1, $value2 = null)
{
	$args = func_num_args() > 1 ?
		[$value1] :
		func_get_args();

	$contents = [];

	foreach ($args as $arg) {
		$arg = [$arg];
		$contents[] = getContents('var_export', $arg);
	}

	$contents = \implode("\n", $contents);
	$contents = \preg_replace(
		[['#=>[\t\s\n\r]+(array)#', '#array \([\t\s\n\r]+\)#']],
		['=> $1', '[]'],
		$contents
	);

	render($contents);
}

function dump($value1, $value2 = null)
{
	$args = func_num_args() === 1 ?
		[$value1] :
		func_get_args();

	$contents = getContents('var_dump', $args);
	$contents = preg_replace(
		['#=>\n#'],
		["=>\n\t"],
		$contents
	);

	render($contents);
}

function render($contents)
{
	static $css;
	if (!$css) {
		$css = true;
		echo '<style>';
		require __DIR__ . '/Global/Dev/render.css';
		echo '</style>';
	}

	$traces = \debug_backtrace();
	// remove self
	\array_shift($traces);

	do {
		$trace = \array_shift($traces);
	} while (
		(isset($trace['function']) && \strpos($trace['function'], 'call_user_func') !== false) ||
		(isset($trace['class']) && Strings::starts(__NAMESPACE__, $trace['class']))
	);

	/**
	 * @var $class
	 * @var $function
	 * @var $file
	 * @var $line
	 */
	\extract($trace);

	if (isset($class)) {
		$function = $class . '::' . $function;
	}

	$function = b($function);

	if (isset($file)) {
		$file = hideRoot($file);
		$file = b($file);
		$line = b($line);
	} else {
		$file = 'self';
	}

	$contents = \htmlspecialchars($contents);

	echo <<<html
<dl class="phpdot-debug">
	<dt>{$function} from {$file} on line {$line}</dt>
	<dd><pre>{$contents}</pre></dd>
</dl>
html;

}

function b(string $value)
{
	return '<b>' . $value . '</b>';
}