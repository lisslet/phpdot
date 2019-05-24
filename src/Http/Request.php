<?php

namespace Dot\Http;

class Request
{
	static function post()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			throw new Error('Bad Connect');
		}
	}
}
