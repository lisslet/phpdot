<?php

namespace Dot\Parser;

abstract class Match {
	const EQUAL = 1;
	const IN = 2;
	const NOT_IN = 3;
	const REGEX = 4;
	const NOT = 5;
}