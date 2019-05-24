<?php

namespace Dot;

require __DIR__ . '/Functions/Array.php';
require __DIR__ . '/Functions/String.php';

function isLocalServer()
{
	return preg_match('#(localhost|(19\d|127)(\.\d+){3})#', $_SERVER['HTTP_HOST']);
}