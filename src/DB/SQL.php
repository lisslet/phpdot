<?php

namespace Dot\DB;

use Dot\DB\Error\DBSQLInvalidTypeError;
use Dot\Strings;
use Dot\Type\Immutable;
use Dot\Type\Pipe;

class SQL implements SQLInterface
{
	const TYPES = ['INSERT', 'SELECT', 'UPDATE', 'REPLACE', 'DELETE'];
	const INSERT = 'INSERT';
	const SELECT = 'SELECT';
	const UPDATE = 'UPDATE';
	const REPLACE = 'REPLACE';
	const DELETE = 'DELETE';

	use Immutable, Pipe;

	/**
	 * @var 'insert' | 'select' | 'update' | 'replace' | 'delete'
	 */
	protected $type;

	/**
	 * @var array
	 */
	protected $tables;

	/**
	 * @var array
	 */
	protected $fields;

	/**
	 * @var arrayf
	 */
	protected $values;

	protected $conditions;

	protected $groupBy;

	protected $orderBy;

	protected $limit;

	protected $offset;

	protected $db;

	/**
	 * @var string part of the `group by` in sql
	 */
	protected $_sqlGroup;

	/**
	 * @var string part of the `order by` in sql
	 */
	protected $_sqlOrder;

	/**
	 * @var string part of `limit` in sql
	 */
	protected $_sqlLimit;

	/**
	 * SQL constructor.
	 * @param DBDriverInterface|string|null $db
	 * @param string|null $type
	 */
	function __construct($db = null, string $type = null)
	{
		$this->reset();
		if ($db) {
			if ($db instanceof DBDriverInterface) {
				$this->db = $db;
				if ($type) {
					$this->type = $type;
				}
			} elseif (\is_string($db)) {
				$this->type = $db;
			}
		}
	}

	function __toString()
	{
		return $this->make();
	}

	function reset()
	{
		$this->type = null;
		$this->tables = [];
		$this->fields = [];
		$this->conditions = [];
		$this->groupBy = null;
		$this->orderBy = null;
		$this->limit = null;
		$this->offset = null;
		$this->db = null;
	}

	const fieldEscapeSign = '`';

	/**
	 * Wrap with the Escape Sign
	 * @param string|array $field
	 * @return string
	 */
	function escapeField($field)
	{

		$sign = SQL::fieldEscapeSign;

		if (\is_array($field)) {
			$field = \implode($sign . ', ' . $sign, $field);
		}

		if ($field === '*' || preg_match('#[a-zA-Z]+\(.*\)#', $field)) {
			return $field;
		}

		return $sign . $field . $sign;
	}

	function escapeValue($value)
	{
		if (\is_array($value)) {
			return Strings::wrap($value, "'", "'", ',');
		}

		return "'" . $value . "'";
	}

	function type(string $type): self
	{
		$type = \strtoupper($type);

		if (!\in_array($type, self::TYPES)) {
			throw new DBSQLInvalidTypeError;
		}

		$this->type = $type;

		return $this;
	}

	function table(string $name)
	{
		$tables = &$this->tables;

		/*
		if (!\preg_match('/^([a-z_][a-z_\d]*)(?:\s+([a-z_][a-z_\d]*_))?$/', $table, $matched)) {
			throw new ErrorArgument('notDBTableName', 1, ['value' => $table]);
		}
		*/

		$tables[] = $name;

		return $this;
	}

	function fields($field)
	{
		$fields = func_get_args();

		foreach ($fields as $field) {
			if (is_array($field)) {
				$this->fields += $field;
			} else {
				$this->fields[] = $field;
			}
		}

		return $this;
	}

	function values(array $values)
	{
		$this->values = $values;

		return $this;
	}

	/**
	 * @param number $limit
	 * @param number|null $offset
	 * @return $this;
	 */
	function limit($limit, $offset = null)
	{
		$this->_sqlLimit = null;

		$this->limit = $limit;
		if ($offset) {
			$this->offset = $offset;
		}

		return $this;
	}

	/**
	 * @param number $offset
	 * @return $this
	 */
	function offset($offset)
	{
		$this->_sqlLimit = null;

		$this->offset = $offset;

		return $this;
	}

	function group(...$fields)
	{
		$this->_sqlGroup = null;

		$this->groupBy += $fields;

		return $this;
	}

	function order($field, $desc = false)
	{
		$this->_sqlGroup = null;

		$orderBy =& $this->orderBy;
		if (\is_array($field)) {
			foreach ($field as $key => $value) {
				$orderBy[] = \is_numeric($key) ?
					[$value] :
					[
						$key,
						\is_bool($value) ?
							$value :
							$value === 'desc'
					];
			}
			foreach ($field as $key => $value) {
				if (\is_numeric($key)) {
					$key = $value;
					$value = false;
				}
				$orderBy[$key] = $value;
			}
		} else {
			$orderBy[$field] = $desc;
		}

		return $this;
	}

	function where($glue, $field = null, $value = null, $logic = '=')
	{
		$num = func_num_args();

		if ($num === 1) {
			$this->conditions[] = $glue;

			return $this;
		}

		// field, value
		if ($num === 2 || ($num === 3 && !\in_array($glue, ['and', 'or']))) {
			$value = $field;
			$field = $glue;
			$glue = 'and';
		}

		if (!$logic) {
			$logic = '=';
		}

		$this->conditions[] = [
			$glue,
			$field,
			$value,
			$logic
		];

		return $this;
	}

	function execute(DBDriverInterface $db = null): DBStateInterface
	{
		if ($db) {
			$this->db = $db;
		}

		return $this->db->execute($this);
	}

	function make(): array
	{
		$type = $this->type;

		$sql = [];

		$tables = &$this->tables;
		if (count($tables) === 1) {
			$table = $tables[0];
		} else {
			$table = $tables[0];
		}

		switch ($type) {
			case SQL::INSERT:
				$sql[] = 'INSERT INTO ' . $table;

				$values = $this->values;
				$sqlIn = [];
				$sqlValues = [];
				foreach($values as $key => $value){
					$sqlIn[] = $this->escapeField($key);
					$sqlValues[] = $this->escapeValue($value);
				}

				$sqlIn = implode(', ', $sqlIn);
				$sqlValues = implode(', ', $sqlValues);

				$sql[] = '(' . $sqlIn . ')';
				$sql[] = 'VALUES(' . $sqlValues . ')';

				break;
			case SQL::UPDATE:
			case SQL::REPLACE:

				$sql[] = $type . ' ' . $table . ' SET ';

				$set = [];
				$values = $this->values;

				foreach ($values as $field => $value) {
					$set[] = $this->escapeField($field) . ' = ' . $this->escapeValue($value);
				}

				$set = implode(', ', $set);
				$sql[] = $set;
				break;
			case SQL::SELECT:
				$sql[] = 'SELECT ';

				$fields = &$this->fields;
				$sets = [];
				foreach ($fields as $field => $as) {
					if (\is_numeric($field)) {
						$sets[] = $this->escapeField($as);
					} else {
						$sets[] = $this->escapeField($field) . ' AS ' . $this->escapeValue($as);
					}
				}

				$sql[] = \implode(', ', $sets);
				$sql[] = ' FROM ' . $table;
				break;
			case SQL::DELETE:
				$sql[] = 'DELETE';
				$sql[] = ' FROM ' . $table;
				break;
		}

		$_sqlGroup = &$this->_sqlGroup;
		if (!$_sqlGroup) {
			$groups = &$this->groupBy;
			$_sqlGroup = $groups ?
				$this->escapeField($groups) :
				'';
		}

		if ($_sqlGroup) {
			$sql[] = $_sqlGroup;
		}


		// todo: use cache
		$conditions = &$this->conditions;
		if ($conditions) {
			$wheres = [];
			foreach ($conditions as $index => $condition) {
				$where = [];
				if (is_string($condition)) {
					if ($index) {
						$where[] = 'AND';
					}
					$where[] = $condition;
				} else {
					list($glue, $field, $value, $logic) = $condition;
					$field = $this->escapeField($field);
					if ($index) {
						$where[] = $glue ? $glue : '';
					}
					$where[] = $field;

					if ($logic === '=' && \is_array($value)) {
						$logic = 'in';
						$value = '(' . $this->escapeValue($value) . ')';
					} else {
						$value = $this->escapeValue($value);
					}

					$where[] = $logic;
					$where[] = $value;
				}
				$wheres[] = \implode(' ', $where);
			}
			$sql[] = 'WHERE ' . \implode(' ', $wheres);
		}

		$_sqlOrder = &$this->_sqlOrder;
		if (!$_sqlOrder) {
			$orders = &$this->orderBy;
			if ($orders) {
				$sqlOrder = [];
				foreach ($orders as $field => $desc) {
					// todo check this
					// $field = $this->escapeField($field);
					$sqlOrder[] = $desc ?
						$field . ' desc' :
						$field;

				}
				$sqlOrder = \implode(', ', $sqlOrder);
				$_sqlOrder = 'ORDER BY ' . $sqlOrder;
			} else {
				$_sqlOrder = '';
			}
		}


		if ($_sqlOrder) {
			$sql[] = $_sqlOrder;
		}


		$this->_makeLimit($sql);

		$sql = \implode(' ', $sql);

		return [
			0        => $sql,
			1        => [],
			'query'  => $sql,
			'params' => []
		];
	}

	function _makeLimit(array &$sql)
	{
		$_sqlLimit = $this->_sqlLimit;
		if (!$_sqlLimit) {
			$limit = &$this->limit;
			if ($limit) {
				$offset = &$this->offset;
				$_sqlLimit = $offset ?
					'LIMIT ' . $offset . ', ' . $limit :
					'LIMIT ' . $limit;
			} else {
				$_sqlLimit = '';
			}
		}
		if ($_sqlLimit) {
			$sql[] = $_sqlLimit;
		}
	}

	function insert(string $table, array $values): SQLInterface
	{
		$this->type = SQL::INSERT;
		$this->values = $values;

		return $this
			->table($table);
	}

	function inserted(string $table, array $values): DBStateInterface
	{
		$this->type = SQL::INSERT;
		$this->values = $values;

		return $this
			->table($table)
			->execute();
	}

	function select(string $table, $fields = '*', $value = null, $field = DB::PRIMARY_KEY): SQLInterface
	{
		$this->type = SQL::SELECT;

		$this->fields($fields);

		if ($value) {
			$this->where($field, $value);
		}

		return $this->table($table);
	}

	function selected(string $table, $fields = '*', $value = null, $field = DB::PRIMARY_KEY): DBStateInterface
	{
		return $this->select($table, $fields, $value, $fields)
			->execute();
	}

	function update(string $table, array $values, $value = null, $field = DB::PRIMARY_KEY): SQLInterface
	{
		$this->type = SQL::UPDATE;
		$this->values = $values;

		if ($value) {
			$this->where($field, $value);
		}

		return $this->table($table);
	}

	function updated(string $table, array $values, $value = null, $field = DB::PRIMARY_KEY): DBStateInterface
	{
		return $this->update($table, $values, $value, $field)
			->execute();
	}

	function replace(string $table, array $values, $value = null, $field = DB::PRIMARY_KEY): SQLInterface
	{
		$this->type = SQL::REPLACE;
		$this->values = $values;

		if ($value) {
			$this->where($field, $value);
		}

		return $this->table($table);
	}

	function replaced(string $table, array $values, $value = null, $field = DB::PRIMARY_KEY): DBStateInterface
	{
		return $this->replace($table, $values, $value, $field)
			->execute();
	}

	function delete(string $table, $value = null, $field = DB::PRIMARY_KEY): SQLInterface
	{
		$this->type = SQL::DELETE;

		if ($value) {
			$this->where($field, $value);
		}

		return $this->table($table);
	}

	function deleted(string $table, $value = null, $field = DB::PRIMARY_KEY): bool
	{
		return $this->delete($table, $value, $field)
			->execute();
	}

	function count()
	{
		$fields = $this->fields;
		$this->fields = ['count(*)' => 'counted'];
		$record = $this->execute()->fetchAssoc();
		$this->fields = $fields;

		return $record['counted'];
	}

	function result()
	{
		return $this->execute()->result();
	}
}