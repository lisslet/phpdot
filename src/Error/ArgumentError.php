<?php

namespace Dot\Error;

use Dot\Text\Texts;

class ArgumentError extends \InvalidArgumentException {
	use Texts;
	function __construct(string $label, int $order = 1, array $values = [])
	{
		$values['order'] = $order;
		parent::__construct(
			$this->_text($label, $values),
			null
		);
	}
}