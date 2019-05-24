<?php

namespace Dot\DB\Error;

use Dot\Error\TextableError;

class DBSQLInvalidArgmentError extends TextableError {
    function __construct($name)
    {
        parent::__construct('invalidArgument', ['name' => $name]);
    }
}