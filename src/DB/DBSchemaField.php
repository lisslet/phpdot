<?php
namespace Dot\DB;

class DBSchemaField
{
	private $schema;
	public $name;
	public $notNull;
	public $autoIncrease;

	/**
	 * DBSchemaField constructor.
	 * @param DBSchema $schema
	 * @param $name
	 */
	function __construct(DBSchema $schema, string $name)
	{
		$this->schema = &$schema;
		$this->name = &$name;
	}

	function unsigned()
	{
	}

	function defaults()
	{
	}

	function primary()
	{
		$this->schema->primary($this->name);

		return $this;
	}

	function autoIncrease()
	{
		$this->autoIncrease = true;
	}

	function notNull()
	{
		$this->notNull = true;
	}
}