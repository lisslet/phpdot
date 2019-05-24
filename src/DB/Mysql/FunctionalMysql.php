<?php

namespace Dot\DB\Mysql;

use Dot\DB\DBDriverBase;
use Dot\DB\DBStateInterface;
use mysql_xdevapi\Exception;

class FunctionalMysql extends DBDriverBase
{

	protected function getHandle()
	{
		$account = &$this->account;
		$handle = \mysqli_connect($account->server, $account->id, $account->password, $account->name);

		return $handle;
	}

	function getState(string $query, array $params = []): DBStateInterface
	{
		$this->handle || $this->open();

		$statement = \mysqli_prepare($this->handle, $query);

		if (!$statement) {
			throw new \Error(\mysqli_error($this->handle));
		}

		if ($params) {
			$types = \str_repeat('s', \count($params));
			\mysqli_stmt_bind_param($statement, $types, ...$params);
		}

		return new MysqlState($statement);
	}

	function close()
	{
		$this->handle && \mysqli_close($this->handle);
	}

	function nativeQuery(string $sql)
	{
		// TODO: Implement nativeQuery() method.
	}

	function query($sql)
	{
		$this->handle or $this->open();

		return new DBResult(\mysqli_query($this->handle, $sql));
	}
}