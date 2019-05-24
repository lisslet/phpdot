<?php
namespace Dot\DB;

interface DBSQLLayerInterface {
	function select($table, $field = '*', $match = null, $matchField = DB::PRIMARY_KEY);

	function insert($table, array $record);

	function update($table, array $record, $match = null, $matchField = DB::PRIMARY_KEY);

	function replace($table, array $record, $match = null, $matchField = DB::PRIMARY_KEY);

	function delete($table, $match = null, $matchField = null);

	function selected($table, $field = '*', $match = null, $matchField = DB::PRIMARY_KEY);

	function paging($table);

	function search($table);

	function sort($table);
}