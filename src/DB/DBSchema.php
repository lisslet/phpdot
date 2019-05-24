<?php

namespace Dot\DB;

class DBSchema implements DBSchemaInterface
{
	private $_fields = [];

	function getSql()
	{
		return $this;
	}

	function addField($type, $name)
	{
		$field = new DBSchemaField($this, $name);
		$field->type = $type;

		$this->_fields[] = &$field;
		$this->{$name} = $field;

		return $field;
	}

	function datetime($name)
	{
		return $this->addField(__FUNCTION__, $name);
	}

	function timestamp($name)
	{
		return $this->addField(__FUNCTION__, $name);
	}

	function time($name)
	{
		return $this->addField(__FUNCTION__, $name);
	}

	function year($name)
	{
		return $this->addField(__FUNCTION__, $name);
	}

	function char($name, $length = 1)
	{
		return $this->addField(__FUNCTION__, $name);
	}

	function varchar($name, $length = 255)
	{
		return $this->addField(__FUNCTION__, $name);
	}

	function tinyblob($name)
	{
		return $this->tinytext($name);
	}

	function tinytext($name)
	{
		return $this->addField(__FUNCTION__, $name);
	}

	function blob($name)
	{
		return $this->text($name);
	}

	function text($name)
	{
		return $this->addField(__FUNCTION__, $name);
	}

	function mediumBlob($name)
	{
		return $this->mediumText($name);
	}

	function mediumText($name)
	{
		return $this->addField(__FUNCTION__, $name);
	}

	function longblob($name)
	{
		return $this->longText($name);
	}

	function longText($name)
	{

	}

	function enum($name, $value1, $value2 = null)
	{
		return $this->addField(__FUNCTION__, $name);
	}

	function set(string $name)
	{
		return $this->addField(__FUNCTION__, $name);
	}
}