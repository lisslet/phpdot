<?php

namespace Dot\Parser;

use Dot\Parser\Error\ParserInvalidTokenArray;
use Dot\Strings;

class Token
{
	const SPACE = [
		'name'      => 'space',
		'match'     => ' ',
		'matchType' => Match::EQUAL,
		'multiple'  => true
	];

	const DOT = [
		'name'      => '.',
		'match'     => '.',
		'matchType' => Match::EQUAL,
		'multiple'  => true
	];

	const REST = [
		'name'      => ',',
		'match'     => ',',
		'matchType' => Match::EQUAL,
		'multiple'  => true
	];

	const NUMERIC = [
		'name'      => 'numeric',
		'match'     => Strings::NUMBERS,
		'matchType' => Match::IN,
		'multiple'  => true
	];

	const PARENTHESES = [
		'name'         => '(',
		'match'        => '(',
		'matchType'    => Match::EQUAL,
		'endsWith'     => ')',
		'endsWithType' => Match::EQUAL,
		'multiple'     => true
	];

	const DOUBLE_QUOTE = [
		'name'         => '"',
		'match'        => '"',
		'matchType'    => Match::EQUAL,
		'endsWith'     => '"',
		'endsWithType' => Match::EQUAL,
		'multiple'     => true
	];

	const QUOTE = [
		'name'         => "'",
		'match'        => "'",
		'matchType'    => Match::EQUAL,
		'endsWith'     => "'",
		'endsWithType' => Match::EQUAL,
		'multiple'     => true
	];

	const GRAVE = [
		'name'         => "`",
		'match'        => "`",
		'matchType'    => Match::EQUAL,
		'endsWith'     => "`",
		'endsWithType' => Match::EQUAL,
		'multiple'     => true
	];

	const STRING = [
		'name'      => 'string',
		'match'     => '#[a-zA-Z\p{L}\p{M}]#',
		'matchType' => Match::REGEX,
		'multiple'  => true
	];

	const ID_STRING = [
		'name'         => 'idString',
		'match'        => Strings::ID_BEGIN,
		'matchType'    => Match::IN,
		'endsWith'     => Strings::ID_NEXT,
		'endsWithType' => Match::NOT_IN,
		'multiple'     => true
	];

	CONST ANY = [
		'name'      => 'any',
		'match'     => '#.#',
		'matchType' => Match::REGEX,
		'multiple'  => false
	];

	public $name;
	public $match;
	public $matchType;
	public $endsWith;
	public $endsWithType;
	public $multiple;

	function __construct($name, string $match = null, string $endsWith = null)
	{
		if (is_array($name)) {
			// todo: die to throw exception
			$token = $name;
			$name = $token['name'] ?? die;
			$match = $token['match'] ?? die;
			$matchType = $token['matchType'] ?? die;
			$endsWith = $token['endsWith'] ?? null;
			$endsWithType = $token['endsWithType'] ?? null;
			$multiple = $token['multiple'] ?? die('multiple');
		} else {
			if (!$match) {
				$match = $name;
			}
			$matchType = getType($match);
			$endsWithType = $endsWith ?? getType($endsWith);
			$multiple = false;
		}

		$this->name = $name;
		$this->match = $match;
		$this->matchType = $matchType;
		$this->endsWith = $endsWith;
		$this->endsWithType = $endsWithType;
		$this->multiple = $multiple;
	}
}

function getType(string $match)
{
	if ($match) {
		if (isset($match[1])) {
			if (Strings::starts($match, '#')) {
				return Match::REGEX;
			}

			return Match::IN;
		}

		return Match::EQUAL;
	}
}