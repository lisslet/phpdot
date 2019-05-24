<?php

namespace Dot\Error;

use Dot\Text\Texts;

class GlobalTextableError extends \Error
{
	use Texts;

	function __construct(string $label, array $values = [])
	{
		parent::__construct(
			$this->_text($label, $values),
			null
		);
	}
}