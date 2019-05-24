<?php

namespace Dot\DB;

class DBResult implements DBResultInterface
{

	/**
	 * @var DBStateInterface
	 */
	protected $statement;

	protected $_current;
	protected $_position;
	protected $_length;

	/**
	 * @var string the fetch type
	 */
	protected $_type;

	/**
	 * @var array result operators
	 * @todo work like pipes
	 */
	protected $operators = [];

	function __construct($result, $type = DB::FETCH_TYPE)
	{
		$this->result = $result;
		$this->_length = $this->getLength();
		$this->_type = $type;
	}

	function map($method, array $args = [])
	{
		if (\is_array($method)) {
			if (\count($method) > 2) {
				$args = $method[2];
			} elseif (\is_string($method[0])) {
				$args = $method[1];
				$method = $method[0];
			}
		}

		$this->operators[] = [\is_callable($method), $method, $args];

		return $this;
	}

	function key(){
		return $this->_position;
	}

	function valid(){
		return $this->_position < $this->_length;
	}

	function rewind()
	{
		$this->_position = -1;
		$this->next();
	}

	function current(){
		$values = $this->_current;
		$setters = $this->operators;
		if($setters){
			foreach($setters as $setter){
				list($callable, $method, $moreArgs) = $setter;

				$values = $callable ?
					$method($values, ...$moreArgs) :
					$method[0]->{$method[1]}($values, ...$moreArgs);
			}
		}

		return $values;
	}

	function next(){
		$this->_position++;
		$this->_current = $this->{$this->_type}();

		return $this->_current;
	}

	function count(){
		return $this->_length;
	}
}