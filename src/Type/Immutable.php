<?php
namespace Dot\Type;

use Dot\Text\Text;
trait Immutable {
	final function __set($name, $value){
		$traces = \debug_backtrace();
		$trace = $traces[0]['class'] === 'Closure' ?
			$traces[1] :
			$traces[0];

		$class = \get_class($this);

		throw new \Error(
			Text::get(
				__NAMESPACE__,
				'immutable',
				['class' => $class]
			),
			null
		);
	}
}