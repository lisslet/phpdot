<?php

namespace Dot\DB;

interface DBDriverInterface extends SQLCommandInterface
{
	function setAccount(DBAccount &$account);

	function open();

	function close();

	function nativeQuery(string $sql);

	function query($sql);

	function prepare(SQLInterface $sql);

	// new build

	function getState(string $query, array $params = []): DBStateInterface;

	function sql(): SQLInterface;

	function execute(SQLInterface $sql);
}