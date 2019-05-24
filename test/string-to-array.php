<?php

namespace Dot;

require '../vendor/autoload.php';

use Dot\Dev;
use Dot\Dev\Bench;


Dev::errors();

$bench = new Bench;

$string = '소리가 되어 나오지 않기를, 다른 얼굴로 변하지 않기를, 글자 옷을 입지 못한 마음의 모양, 그대로의 모습으로 내 안에 있기를';

$bench->execute('substr', function () use ($string) {
	$target = $string;
	$length = strlen($target);
	$index = -1;
	$result = [];
	while (++$index < $length) {
		$char = substr($target, $index);
		$result[] = $char;
	}
});

$bench->execute('substr sugar', function () use ($string) {
	$target = $string;
	$length = strlen($target);
	$index = -1;
	$result = [];
	while (++$index < $length) {
		$char = $target[$index];
		$result[] = $char;
	}
});

$bench->execute('str_split', function () use ($string) {
	$target = str_split($string);
	$length = count($target);
	$index = -1;
	$result = [];
	while (++$index < $length) {
		$char = $target[$index];
		$result[] = $char;
	}
});

$bench->asTable();