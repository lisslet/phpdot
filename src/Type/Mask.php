<?php
namespace Dot\Type;

trait Mask {
	static function __set_state($an_array)
	{
		// TODO: Implement __set_state() method.
	}

	function __debugInfo(){
		if (!isset($this->__mask__)) {
			return (array)$this;
		}

		$mask = $this->__mask__;
		$values = [];
		foreach ($this as $name => $value) {
			if ($name === '__mask__') {
				continue;
			}

			if (in_array($name, $mask)) {
				$type = \gettype($value);
				$value = $type === 'object' ?
					\get_class($value) :
					$type;

				$value = 'masked ' . $value;
			}
			$values[$name] = $value;
		}

		return $values;
	}
}