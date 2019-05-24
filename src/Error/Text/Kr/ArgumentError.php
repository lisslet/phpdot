<?php

namespace Dot\Error\Text\Kr;

abstract class ArgumentError {
	const notDBTableName = '{$order}번째 인자 값({$value})은 테이블 명이 아닙니다.';
	const required = '{$order}번째 인자는 필수 요소 입니다.';
}