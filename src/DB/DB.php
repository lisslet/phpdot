<?php

namespace Dot\DB;

use Dot\Type\Immutable;

if (!\defined(__NAMESPACE__ . '\\PRIMARY_KEY')) {
	\define(__NAMESPACE__ . '\\' . 'PRIMARY_KEY', 'id');
}

if (!\defined(__NAMESPACE__ . '\\FETCH_TYPE')) {
	\define(__NAMESPACE__ . '\\' . 'FETCH_TYPE', 'fetchAssoc');
}

abstract class DB
{
	const BOTH = 'fetchBoth';
	const ASSOC = 'fetchAssoc';
	const FETCH_TYPE = FETCH_TYPE;
	const PRIMARY_KEY = PRIMARY_KEY;
}