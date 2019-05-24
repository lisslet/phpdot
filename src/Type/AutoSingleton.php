<?php
namespace Dot\Type;

trait AutoSingleton {
	use Singleton;

	function __construct()
	{
		$this->__construct__();
	}
}