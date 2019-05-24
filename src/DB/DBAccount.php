<?php

namespace Dot\DB;

CLASS DBAccount
{

	/**
	 * @var string
	 */
	public $type = 'mysql';

	/**
	 * @var string
	 */
	public $id;

	/**
	 * @var string
	 */
	public $password;

	/**
	 * @var string
	 */
	public $server;

	/**
	 * @var number | string
	 */
	public $port;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $charset = 'utf-8';

	/**
	 * DBAccount constructor.
	 * @param string | array $id
	 * @param string $password
	 * @param string $name
	 * @param string $server
	 */
	function __construct($id = null, $password = null, $name = null, $server = null)
	{
		if ($id) {
			if (\is_array($id)) {
				\extract($id);
			}
			$this->id = $id;
		}

		if ($password) {
			$this->password = $password;
		}

		if ($server) {
			$this->server = $password;
		}

		if ($name) {
			$this->name = $name;
		}
	}

	/**
	 * set account value
	 * @param string $name
	 * @param number | string $value
	 * @return $this
	 */
	function set($name, $value)
	{
		$this->{$name} = $value;

		return $this;
	}

	/**
	 * check the states of account account has required values
	 * @return bool
	 */
	function isAvailable()
	{
		return $this->id && $this->password && $this->name;
	}
}