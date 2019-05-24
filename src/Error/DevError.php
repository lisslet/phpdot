<?php

namespace Dot\Error;

class DevError extends \Exception {
	function __construct(string $message)
	{
		parent::__construct($message, null);
	}
}