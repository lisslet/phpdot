<?php
namespace Dot\Type;

trait ImmutableSingleton {
	use AutoSingleton;
	use Immutable;
}