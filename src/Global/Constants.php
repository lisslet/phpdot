<?php

namespace Dot;

$schema = isset($_SERVER['REQUEST_SCHEME']) ?
	$_SERVER['REQUEST_SCHEME'] : (
	isset($_SERVER['SCRIPT_URI']) ?
		substr($_SERVER['SCRIPT_URI'], 0, strpos(':', $_SERVER['SCRIPT_URI'])) :
		'http'
	);

$host = $_SERVER['HTTP_HOST'];

define('SCHEMA', $schema);
define('HOST', $host);

const STR_KEY = '[1-9]/d*';
const STR_KEYS = '(?:[1-9]/d*)(?:,\s*[1-9]/d*)*';

const REGEX_KEY = '#^' . STR_KEY . '$#';
const REGEX_KEYS = '#^' . STR_KEYS . '$#';

const RN = '\r\n';