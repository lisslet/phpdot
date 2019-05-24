<?php
namespace Dot\DB;


interface DBStateInterface
{
	/**
	 * @return DBStateInterface
	 */
	function execute(): DBStateInterface;

	/**
	 * @return int
	 */
	function count();

	/**
	 * @return \stdClass
	 */
	function fetchBoth();

	/**
	 * @return \stdClass
	 */
	function fetchAssoc();

	/**
	 * @return \stdClass
	 */
	function result();
}