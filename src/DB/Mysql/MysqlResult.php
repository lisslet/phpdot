<?php

namespace Dot\DB\Mysql;

use Dot\DB\DBResult;
class MysqlResult extends DBResult
{
	/**
	 * @var \mysqli_result;
	 */
	protected $result;

	function getLength(){
		return $this->result->num_rows;
	}

	function fetch(){
		return $this->fetchAssoc();
	}
	/**
	 * @return \stdClass
	 */
	function fetchBoth()
	{
		return $this->result->fetch_array( MYSQLI_BOTH);
	}

	/**
	 * @return array
	 */
	function fetchAssoc()
	{
		return $this->result->fetch_assoc();
	}

	function all(){
		$records = [];
		foreach($this as $record){
			$records[] = $record;
		}
		return $records;
	}
}