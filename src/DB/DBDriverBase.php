<?php

namespace Dot\DB;

abstract class DBDriverBase implements DBDriverInterface
{
	/**
	 * @var DBAccount
	 */
	protected $account;
	public $handle;

	function __construct(DBAccount &$account = null)
	{
		$account && $this->setAccount($account);
	}

	function _getDBDriver()
	{
		return $this;
	}

	function _DBSQLExecuted(SQLInterface $sql)	{
	}

	/**
	 * set db account
	 * @param DBAccount $account
	 * @return $this
	 */
	function setAccount(DBAccount &$account){
		$this->account = $account;

		return $this;
	}

	function open()
	{
		$account = &$this->account;
		if(!$account){
			throw new \ErrorException('Account was not set');
		}

		if(!$account->isAvailable()){
			throw new \ErrorException('Account has not required values');
		}

		$this->handle = $this->getHandle();
	}

	abstract protected function getHandle();

	abstract function getState(string $query, array $params = []): DBStateInterface;

	function prepare(SQLInterface $sql){

		list($query, $params) = $sql->make();


		$state = $this->getState($query, $params);
		// $statement->setSql($sql);
		// $statement->setCaller($sql->caller);

		return $state;
	}

	function execute(SQLInterface $sql){
		list($query, $params) = $sql->make();

		// var_dump($query);
		$state = $this->getState($query, $params);

		return $state->execute();
	}

	function insertedId(){
		return $this->handle->inserted();
	}

	function sql(): SQLInterface {
		return new SQL($this);
	}

	function insert(string $table, array $values): SQLInterface {
		return $this->sql()
			->insert($table, $values);
	}

	function inserted(string $table, array $values): DBStateInterface {
		return $this->execute(
			$this->sql()
				->insert($table, $values)
		);
	}

	function select(string $table, $fields = '*', $value = null, $field = DB::PRIMARY_KEY): SQLInterface {
		return $this->sql()
			->select($table, $fields, $value, $field);
	}

	function selected(string $table, $fields = '*', $value = null, $field = DB::PRIMARY_KEY): DBStateInterface {
		return $this->execute(
			$this->sql()
				->select($table, $fields, $value, $field)
		);
	}

	function update(string $table, array $values, $value = null, $field = DB::PRIMARY_KEY): SQLInterface {
		return $this->sql()
			->update($table, $values, $value, $field);
	}

	function updated(string $table, array $values, $value = null, $field = DB::PRIMARY_KEY): DBStateInterface {
		return $this->execute(
			$this->sql()->update($table, $values, $value, $field)
		);
	}

	function replace(string $table, array $values, $value = null, $field = DB::PRIMARY_KEY): SQLInterface {
		return $this->sql()
			->replace($table, $values, $value, $field);
	}

	function replaced(string $table, array $values, $value = null, $field = DB::PRIMARY_KEY): DBStateInterface {
		return $this->execute(
			$this->sql()->replace($table, $values, $value, $field)
		);
	}

	function delete(string $table, $value = null, $field = DB::PRIMARY_KEY): SQLInterface {
		return $this->sql()
			->delete($table, $value, $field);
	}

	function deleted(string $table, $value = null, $field = DB::PRIMARY_KEY): bool {
		return $this->execute(
			$this->sql()
				->delete($table, $value, $field)
		);
	}
}