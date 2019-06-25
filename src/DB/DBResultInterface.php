<?php
namespace Dot\DB;


interface DBResultInterface extends \Iterator
{
    function count(): int;
}