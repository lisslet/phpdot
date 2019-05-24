<?php

namespace Dot\DB;

interface SQLCommandInterface
{

	function insert(string $table, array $values): SQLInterface;

	function inserted(string $table, array $values): DBStateInterface;

	function select(string $table, $fields = '*', $value = null, $field = DB::PRIMARY_KEY): SQLInterface;

	function selected(string $table, $fields = '*', $value = null, $field = DB::PRIMARY_KEY): DBStateInterface;

	function update(string $table, array $values, $value = null, $field = DB::PRIMARY_KEY): SQLInterface;

	function updated(string $table, array $values, $value = null, $field = DB::PRIMARY_KEY): DBStateInterface;

	function replace(string $table, array $values, $value = null, $field = DB::PRIMARY_KEY): SQLInterface;

	function replaced(string $table, array $values, $value = null, $field = DB::PRIMARY_KEY): DBStateInterface;

	function delete(string $table, $value = null, $field = DB::PRIMARY_KEY): SQLInterface;

	function deleted(string $table, $value = null, $field = DB::PRIMARY_KEY): bool;
}