<?php

class Language
{
	protected $tokens = [];

	function token($name, $match)
	{
		$this->tokens[] = new Token($name, $match);
	}

	function scan(string $chars)
	{
		$result = [];
		$tokens = &$this->tokens;
		$length = strlen($chars);
		$max = 100;
		$current = 0;
		do {
			foreach ($tokens as $token) {
				if ($t = $token->test($chars, $current)) {
					$result[] = $t;
					continue 2;
				}
			}
			if (--$max === 0) {
				die;
			}
			echo $chars, '<hr>';
		} while ($chars);


		return $result;
	}
}

class Token
{
	public $type;
	public $match;
	public $matchType;

	function __construct($type, $match)
	{
		$matchType = $match[0] === '#' && substr($match, -1, 1) === '#' ? 1 : 0;
		if ($matchType === 1) {
			$match = '#^' . substr($match, 1);
		}

		$this->type = $type;
		$this->match = $match;
		$this->matchType = $matchType;
	}

	function test(&$chars, &$current)
	{
		$match = &$this->match;
		if ($this->matchType === 1) {
			if (preg_match($match, $chars, $matches)) {
				$matches = $matches[0];

				return $this->result($chars, $matches);
			}
		} else {
			$char = $chars[0];
			if ($char === $match) {
				return $this->result($chars, $char);
			}
		}

		return false;
	}

	function result(&$chars, $value)
	{
		// $chars = substr($chars, strlen($value));
		$chars = ltrim($chars, $value);

		$type = &$this->type;
		return "&lt;{$type}> <b>{$value}</b>";
		/*
		return [
			'type'  => $this->type,
			'value' => $value
		];
		*/
	}
}

$fields = new Language;

$fields->token('parentheses', '(');
$fields->token('parentheses', ')');
$fields->token('numeric', '#\d(\.\d+)*#');
$fields->token('quote', "'");
$fields->token('doubleQuote', '"');
$fields->token('space', '#\s+#');
$fields->token('rest', ',');
$fields->token('field', '#[a-z][a-zA-Z_\d]*#');

echo '<pre style="float:left;width:50%">';
var_export($fields);
echo '</pre>';

$test = "field, old_name as new name, method(), method(field, 1, 'value')";
echo '<pre style="float:left;width:50%">';
var_export($fields->scan($test));
echo '</pre>';