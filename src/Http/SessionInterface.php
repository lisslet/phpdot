<?php
namespace Dot\Http;

interface SessionInterface {
	function set(string $name, $value);

	function get(string $name);
}