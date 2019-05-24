<?php

use Dot\Dev;
use Dot\Dev\Bench;

require '../vendor/autoload.php';

Dev::errors();

$bench = new Bench;

$numbers = 'abcdefghijklmnopqrstuvwxyz0123456789_';
$regex = '#[\w_]#';
$needles = ['a', 'b', 'c', 'd', 'e', 'f', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

$bench->execute('regexp', function () use ($regex, $numbers, $needles) {
	foreach ($needles as $needle) {
		if (preg_match($regex, $needle)) {
			continue;
		}
	}
});

$bench->execute('strpos', function () use ($regex, $numbers, $needles) {
	foreach ($needles as $needle) {
		if (strpos($numbers, $needle) !== false) {
			continue;
		}
	}
});

$bench->execute('stripos', function () use ($regex, $numbers, $needles) {
	foreach ($needles as $needle) {
		if (stripos($numbers, $needle) !== false) {
			continue;
		}
	}
});

$bench->execute('in_array', function () use ($regex, $numbers, $needles) {
	foreach ($needles as $needle) {
		if (in_array($needle, $needles)) {
			continue;
		}
	}
});

echo '<meta charset="utf-8">';
$bench->asTable();