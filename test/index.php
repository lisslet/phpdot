<?php

namespace Dot;

use Dot\DB\DBAccount;
use Dot\DB\Mysql\Mysql;
use Dot\DB\SQL;

require '../vendor/autoload.php';

Dev::errors();

$account = new DBAccount('root', 'autoset', 'phpdot');
$db = new Mysql($account);
//$records = $db->selected('test');

$sql = new SQL;

dump($sql->select('tableName', 'count(*) as aa, method(method(1, "t\\"e, test()st", field)), c as cc, d')->__toString());
dump($sql->update('table', ['a' => 1, 'b' => 2])->__toString());