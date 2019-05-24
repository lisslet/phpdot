<?php

namespace Dot\Parser;

use Dot\Dev\Console;
use Dot\Strings;
use Dot\Type\AutoSingleton;

abstract class ParserBase
{
	// use AutoSingleton;

	/**
	 * @var Token[]
	 */
	protected const _TOKENS = [
		'space' => [
			'multiple' => true,
			'type'     => Match::EQUAL,
			'matches'  => [
				' '
			]
		],

		'.' => [
			'multiple' => true,
			'type'     => Match::EQUAL,
			'matches'  => [
				'.'
			]
		],

		',' => [
			'multiple' => true,
			'type'     => Match::EQUAL,
			'matches'  => [
				','
			]
		],

		'numeric' => [
			'multiple' => true,
			'type'     => Match::IN,
			'matches'  => [
				Strings::NUMBERS,
			]
		],

		'(' => [
			'multiple' => true,
			'type'     => Match::EQUAL,
			'matches'  => [
				'('
			]
		],

		')' => [
			'multiple' => true,
			'type'     => Match::EQUAL,
			'matches'  => [
				')'
			]
		],

		'"' => [
			'multiple' => true,
			'type'     => Match::EQUAL,
			'matches'  => [
				'"'
			]
		],

		"'" => [
			'multiple' => true,
			'type'     => Match::EQUAL,
			'matches'  => [
				"'"
			]
		],

		'`' => [
			'multiple' => true,
			'type'     => Match::EQUAL,
			'matches'  => [
				"`"
			]
		],


		'id' => [
			'multiple' => true,
			'type'     => Match::IN,
			'matches'  => [
				Strings::ID_BEGIN,
				Strings::ID_NEXT
			]
		],

		'string' => [
			'multiple' => true,
			'type'     => Match::REGEX,
			'matches'  => [
				'#[a-zA-Z\p{L}\p{M}]#'
			]
		],

		'any' => [
			'multiple' => false,
			'type'     => Match::REGEX,
			'matches'  => [
				'#.#'
			]
		]
	];

	const _SYNTAX = [
		'(' => [
			'match'     => '(',
			'endsWith'  => ')',
			'space'     => false,
			'stringify' => false
		],

		'`' => [
			'match'     => '`',
			'endsWith'  => '`',
			'space'     => true,
			'stringify' => true
		]
	];

	static function parse(string $codes)
	{
		$tokens = tokenizer(self::_TOKENS, $codes);
		$parse = parse(self::_SYNTAX, $tokens);

		return $parse;
	}
}

function tokenizer(array $tokens, string $codes)
{
	$length = \strlen($codes);
	$current = 0;
	$result = [];
	while ($current < $length) {
		$char = $codes[$current];


		foreach ($tokens as $name => $token) {

			$multiple = $token['multiple'];
			$type = $token['type'];

			$matches = $token['matches'];
			$matchEnd = count($matches) - 1;
			$matchIndex = 0;
			$match = $matches[$matchIndex];

			if ($type === Match::EQUAL) {
				if ($char === $match) {
					if ($multiple) {
						$value = '';
						while ($current < $length) {
							$char = $codes[$current];
							if ($char === $match) {
								$value .= $char;
								$current++;
							} elseif ($matchIndex++ < $matchEnd) {
								$match = $matches[$matchIndex];
							} else {
								break;
							}
						}
					} else {
						$value = $char;
						$current++;
					}
					$result[] = [
						'name'  => $name,
						'value' => $value
					];
					continue 2;
				}
			} elseif ($type === Match::IN) {
				if (\strpos($match, $char) !== false) {
					if ($multiple) {
						$value = '';
						while ($current < $length) {
							$char = $codes[$current];
							if (\strpos($match, $char) !== false) {
								$value .= $char;
								$current++;
							} elseif ($matchIndex++ < $matchEnd) {
								$match = $matches[$matchIndex];
							} else {
								break;
							}
						}
					} else {
						$value = $char;
						$current++;
					}
					$result[] = [
						'name'  => $name,
						'value' => $value
					];
					continue 2;
				}
			} elseif ($type === Match::REGEX) {
				if (\preg_match($match, $char)) {
					if ($multiple) {
						$value = '';
						while ($current < $length) {
							$char = $codes[$current];
							if (\preg_match($match, $char)) {
								$value .= $char;
								$current++;
							} elseif ($matchIndex++ < $matchEnd) {
								$match = $matches[$matchIndex];
							} else {
								break;
							}
						}
					} else {
						$value = $char;
						$current++;
					}
					$result[] = [
						'name'  => $name,
						'value' => $value
					];
					continue 2;
				}
			}
		}
		$current++;
	}

	return $result;
}

function parse(array $syntaxes, array $tokens)
{
	$current = 0;
	$length = count($tokens);
	$tree = [];
	while ($current < $length) {
		$token = $tokens[$current];
		$name = $token['name'];
		if ($name === 'space') {
			$current++;
			continue;
		}
		foreach ($syntaxes as $syntaxName => $syntax) {
			$match = $syntax['match'];
			$spaceable = $syntax['space'];
			$stringify = $syntax['stringify'];
			if ($match === $name) {
				$endsWith = $syntax['endsWith'];
				$current++;
				$arguments = [];

				if ($stringify) {
					while ($current < $length) {
						$next = $tokens[$current++];
						if ($next['name'] === $endsWith) {
							break;
						}
						if ($spaceable || $next['name'] !== 'space') {
							$arguments[] = $next['value'];
						}
					};
					$token['arguments'] = implode('', $arguments);
				} else {
					while ($current < $length) {
						$next = $tokens[$current++];
						if ($next['name'] === $endsWith) {
							break;
						}
						if ($spaceable || $next['name'] !== 'space') {
							$arguments[] = $next;
						}
					};
					$token['arguments'] = $arguments;
				}
				continue;
			}
		}

		$tree[] = $token;
		$current++;
	}

	return $tree;
}