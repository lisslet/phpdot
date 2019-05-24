<?php

namespace Dot\DB\Mysql;

use Dot\DB\DBDriverBase;
use Dot\DB\DBStateInterface;
use Dot\DB\Error\DBSQLError;

class Mysql extends DBDriverBase {
	/**
	 * @var \mysqli_stmt
	 */
	public $handle;

	/** @noinspection PhpComposerExtensionStubsInspection */
	protected function getHandle(): \mysqli
	{
		$account =& $this->account;
		/** @noinspection PhpComposerExtensionStubsInspection */
		return new \mysqli($account->server, $account->id, $account->password, $account->name);
	}

	function getState($query, array $params = []): DBStateInterface
	{
		$this->handle || $this->open();

		$state = $this->handle->prepare($query);

		if(!$state){
			throw new DBSQLError($query);
		}

		if($params){
			$types = \str_repeat('s', count($params));
			$state->bind_param($types, ...$params);
		}

		return new MysqlState($state);
	}

	function close()
	{
		// TODO: Implement close() method.
	}

	function query($sql)
	{
		// TODO: Implement query() method.
	}

	function nativeQuery(string $sql){

	}
}