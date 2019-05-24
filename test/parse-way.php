<?php

namespace Dot;

require '../vendor/autoload.php';

use Dot\Dev;
use Dot\Dev\Bench;

Dev::errors();

$bench = new Bench;

$bench->loop = 100;

$string = "1, 2, 3 and 4 tell me truth";

$regexs1 = [
	'#\w#',
	'#\s#'
];

$regexs2 = [
	'#^\w+#',
	'#^\s+#'
];


$bench->execute('multi char', function () use ($string, $regexs2) {
	$target = $string;

	$tokens = [];
	while ($target) {
		foreach($regexs2 as $regex){
			if (preg_match($regex, $target, $match)) {
				$tokens[] = $match[0];

				//$target = ltrim($target, $match[0]);
				$target = substr($target, strlen($match[0]));
				continue 2;
			}
		}
		$char = $target[0];
		//$target = ltrim($target, $char);
		$target = substr($target, 1);
		$tokens[] = $char;
	}
});


$bench->execute('single chars', function () use ($string, $regexs1) {
	$target = $string;
	$length = strlen($target);
	$index = -1;

	$tokens = [];
	while (++$index < $length) {
		$char = $target[$index];

		$token = '';
		foreach($regexs1 as $regex){
			if (preg_match($regex, $char)) {
				while($index < $length){
					if(preg_match($regex, $char)) {
						$char = $target[$index++];
						$token .= $char;
					}else{
						$tokens[] = $token;
						continue 3;
					}
				}
				$tokens[] = $token;
				continue 2;
			}
		}

		$tokens[] = $char;
	}
});

echo '<meta charset="utf-8">';

$bench->asTable();