<?php

namespace Dot\DB;

interface SQLLinkageInterface {
	/**
	 * insert query
	 * @param string $table
	 * @param array $values
	 * @return SQLInterface
	 */
	function insert(string $table, array $values): SQLInterface;

	/**
	 * insert result
	 * @param string $table
	 * @param array $record
	 * @return mixed
	 */
	function inserted(string $table, array $record): bool;

	/**
	 * select query
	 * @param string $table
	 * @param string $fields
	 * @param null $value
	 * @param string $field
	 * @return SQLInterface
	 */
	function select(string $table, $fields = '*', $value = null, $field = DB::PRIMARY_KEY): SQLInterface;

	/**
	 * select result
	 * @param string $table
	 * @param string $fields
	 * @param null $value
	 * @param string $field
	 * @return DBResultInterface
	 */
	function selected(string $table, $fields = '*', $value = null, $field = DB::PRIMARY_KEY): DBResultInterface;

	/**
	 * update query
	 * @param string $table
	 * @param array $values
	 * @param null $value
	 * @param string $field
	 * @return SQLInterface
	 */
	function update(string $table, array $values, $value = null, $field = DB::PRIMARY_KEY): SQLInterface;

	/**
	 * update result
	 * @param string $table
	 * @param array $values
	 * @param null $value
	 * @param string $field
	 * @return array the $values
	 */
	function updated(string $table, array $values, $value = null, $field = DB::PRIMARY_KEY): array;

	/**
	 * replace query
	 * @param string $table
	 * @param array $values
	 * @param null $value
	 * @param string $field
	 * @return SQLInterface
	 */
	function replace(string $table, array $values, $value = null, $field = DB::PRIMARY_KEY): SQLInterface;

	/**
	 * replace result
	 * @param string $table
	 * @param array $values
	 * @param null $value
	 * @param string $field
	 * @return array the $values
	 */
	function replaced(string $table, array $values, $value = null, $field = DB::PRIMARY_KEY): array;

	/**
	 * delete query
	 * @param string $table
	 * @param null $value
	 * @param string $field
	 * @return SQLInterface
	 */
	function delete(string $table, $value = null, $field = DB::PRIMARY_KEY): SQLInterface;

	/**
	 * delete result
	 * @param string $table
	 * @param null $value
	 * @param string $field
	 * @return bool
	 */
	function deleted(string $table, $value = null, $field = DB::PRIMARY_KEY): bool;

}