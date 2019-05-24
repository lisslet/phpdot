<?php

namespace Dot\DB\Error;

use Dot\Error\TextableError;

class DBSQLInvalidTypeError extends TextableError {
    function __construct()
    {
        parent::__construct('invalidType');
    }
}