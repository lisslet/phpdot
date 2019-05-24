<?php

namespace Dot\DB\Mysql;

use Dot\DB\DBResultInterface;
use Dot\DB\DBStateBase;
use Dot\DB\DBStateInterface;

class MysqlState extends DBStateBase
{

	/**
	 * @var \mysqli_stmt
	 */
	protected $_state;

	/**
	 * @var \mysqli_result
	 */
	protected $result;

	/**
	 * @return DBResultInterface
	 */
	protected function getResult(): DBResultInterface
	{
		return new MysqlResult($this->_state->get_result());
	}

	function result(): DBResultInterface
	{
		return new MysqlResult($this->_state->get_result());
	}

	/**
	 * @return int
	 */
	function count()
	{
		// TODO: Implement count() method.
	}
}