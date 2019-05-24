<?php

namespace Dot\Error;

use Dot\Text\UnifiedTexts;

class TextableError extends \Error
{
	use UnifiedTexts;

	function __construct(string $label, array $values = [])
	{
		parent::__construct(
			$this->_text($label, $values),
			null
		);
	}
}