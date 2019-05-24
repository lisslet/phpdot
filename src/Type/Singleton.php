<?php

namespace Dot\Type;

use Dot\Text\Text;

trait Singleton
{
	/**
	 * construct state
	 * @var boolean
	 */
	protected $__constructed__;

	/**
	 * Check if validation function is called
	 * @throws Exception
	 */
	function __destruct()
	{
		if (!$this->__constructed__) {
			$class = \get_class($this);
			// todo using dev exception
			throw new \Error(
				Text::get(
					__NAMESPACE__,
					'singletonConstructor',
					['class' => $class]
				)
			);
		}
	}

	/**
	 * Check if constructor is called after first constructed
	 * @throws ErrorException
	 */
	final function __construct__()
	{
		static $__constructed__ = [];
		$className = \get_class($this);
		if (isset($__constructed__[$className])) {
			throw new \Error(
				Text::get(
					__NAMESPACE__,
					'singleton',
					['class' => $className]
				)
			);
		}
		$__constructed__ [$className] = true;
		$this->__constructed__ = true;
	}
}