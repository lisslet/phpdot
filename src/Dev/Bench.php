<?php

namespace Dot\Dev;

class Bench
{
	/**
	 * @var BenchGroup[];
	 */
	protected $_groups = [];

	protected $_groupIndex = 0;

	public $section = 10;
	public $loop = 10000;

	function group(string $name = null)
	{
		$groupIndex =& $this->_groupIndex;
		$groupIndex++;
		if (!$name) {
			$name = 'group' . $groupIndex;
		}

		$group = new BenchGroup($this);

		$this->_groups[$name] = $group;

		return $group;
	}

	function begin(string $name = null)
	{
		return $this
			->group($name)
			->begin();
	}

	function end(string $name)
	{
		$groups = &$this->_groups;
		if (isset($groups[$name])) {
			return $groups[$name]->end();
		}
		throw new Error("{$name} is not BenchGroup");
	}

	function execute($name, callable $method = null)
	{
		if (func_num_args() < 2) {
			$method = $name;
			$name = null;
		}

		return $this->group($name)
			->execute($method);
	}

	function asTable()
	{
		$groups = &$this->_groups;

		$totalDurations = [];
		foreach ($groups as $name => $group) {
			$durations = [];
			foreach ($group->begin as $index => $begin) {
				$end = $group->end[$index];
				$durations[] = $end->format('U.u') - $begin->format('U.u');
			}

			array_splice($durations, array_search(min($durations), $durations), 1);
			array_splice($durations, array_search(max($durations), $durations), 1);

			$durations[] = array_sum($durations) / $this->section;
			$totalDurations[] = $durations;
		}

		$section = $this->section;
		$groupCounted = count($totalDurations);

		echo '<table border="1">';

		echo '<tr>';
		foreach ($groups as $name => $group) {
			echo '<th>' . $name . '</th>';
		}
		echo '</tr>';

		while ($section-- > 0) {
			echo '<tr>';
			$cells = $groupCounted;
			$cellIndex = -1;
			while (++$cellIndex < $cells) {
				$cell = $totalDurations[$cellIndex][$section];
				echo '<td>' , $cell , '</td>';
			}
			echo '</tr>';
		}
		echo '</table>';
	}
}

class BenchGroup
{
	/**
	 * @var Bench
	 */
	protected $_bench;

	/**
	 * @var \DateTime[]
	 */
	public $begin = [];

	/**
	 * @var \DateTime[]
	 */
	public $end = [];

	function __construct(Bench &$bench)
	{
		$this->_bench = &$bench;
	}

	function begin()
	{
		$this->begin[] = new \DateTime;

		return $this;
	}

	function end()
	{
		$this->end[] = new \DateTime;

		return $this;
	}

	function execute(callable $method)
	{
		$section = $this->_bench->section + 2;

		while ($section-- > 0) {
			$this->begin();

			$loop = $this->_bench->loop;
			while ($loop-- > 0) {
				$method();
			}

			$this->end();
		}

		return $this;
	}
}