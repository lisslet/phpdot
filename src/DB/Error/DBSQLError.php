<?php

namespace Dot\DB\Error;

use Dot\Error\TextableError;

class DBSQLError extends TextableError {
	function __construct($sql)
	{
		parent::__construct('sql', ['value' => $sql]);
	}
}