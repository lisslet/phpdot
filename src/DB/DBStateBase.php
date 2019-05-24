<?php

namespace Dot\DB;

abstract class DBStateBase implements DBStateInterface {
	public $caller;
	protected $sql;
	protected $_state;
	protected $result;

	function __construct($state){
		$this->_state = $state;
	}

	/**
	 * @return DBStateInterface
	 */
	function execute(): DBStateInterface
	{
		$this->_state->execute();
		return $this;
	}

	/**
	 * @return DBResultInterface
	 */
	abstract protected function getResult(): DBResultInterface;

	/**
	 * @param string $type the type of variable
	 * @return \stdClass
	 * @throws Error
	 */
	function fetch($type = DB::FETCH_TYPE)
	{
		switch ($type) {
			case DB::ASSOC :
				return $this->fetchAssoc();
				break;
			case DB::BOTH :
				return $this->fetchBoth();
				break;
			default:
				throw new \Error('Wrong type for fetch');
		}
	}
	/*
	function result($setter = null, $type = DB::FETCH_TYPE): DBResultInterface
	{
		$caller = &$this->caller;
		$sql = &$this->sql;

		$result = new DBResult($this, $type);

		if ($caller) {
			$callerSetter = $this->caller->_getDBResultSetter();
			$callerSetter && $result->setter($callerSetter);
		}

		if ($setter) {
			$result->setter($setter);
		}

		if ($sql->paging['use']) {
			$paging = &$sql->paging;
			$paging = new Paging($paging['totalPage'], $paging['page']);
			$paging->setIterator($result);

			return $paging;
		}


		return $result;
	}

	function all($index = null, $value = null)
	{
		$result = [];
		if ($index) {
			if ($value) {
				while ($record = $this->fetch()) {
					$result[$record[$index]] = $record[$value];
				}
			} else {
				while ($record = $this->fetch()) {
					$result[$record[$index]] = $record;
				}
			}
		} else {
			if ($value) {
				while ($record = $this->fetch()) {
					$result[] = $record[$value];
				}
			} else {
				while ($record = $this->fetch()) {
					$result[] = $record;
				}
			}
		}

		return $result;
	}

	/**
	 * @return \stdClass
	 */
	abstract function result(): DBResultInterface;

	function fetchBoth()
	{
		return $this->result()->fetchBoth();
	}

	/**
	 * @return \stdClass
	 */
	function fetchAssoc()
	{
		return $this->result()->fetchAssoc();
	}
	/*
	function setCaller(DBSQLCallerInterface $caller)
	{
		$this->caller = $caller;
	}

	function setSql(DBSQLInterface $sql)
	{
		$this->sql = $sql;
	}
	*/
}