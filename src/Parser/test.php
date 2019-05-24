<?php

namespace Dot;

use Dot\Parser\ParserBase;
use Dot\Parser\Token;

require '../../vendor/autoload.php';

Dev::errors();

class SQLFieldParser extends ParserBase
{

}


$result = SQLFieldParser::parse('field1, field2_name as rename, method(123), method(method(field, 123)), table.field as `test`');

li($result);

function li($items)
{
	echo '<ul>';
	foreach ($items as $item) {
		echo '<li><b>' . $item['name'] . '</b>: ' . $item['value'];
		if (isset($item['arguments'])) {
			if (is_array($item['arguments'])) {
				li($item['arguments']);
			} else {
				echo '<blockqoute>', $item['arguments'] . '</blockqoute>';
			}
		}
		echo '</li>';
	}
	echo '</ul>';
}