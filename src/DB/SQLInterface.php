<?php

namespace Dot\DB;

interface SQLInterface extends SQLCommandInterface
{

	function __toString();

	function execute(DBDriverInterface $db = null);

	function make(): array;
}