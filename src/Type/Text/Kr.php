<?php

namespace Dot\Type\Text;

abstract class Kr {
	const immutable = '불변으로 지정된 클래스({$class} use Immutable)에는 변수를 변경 할 수 없습니다.';
	const singletonConstructor = '단일로 제한된 클래스({$class} use Singleton)에서 __construct__ 가 실행되지 않았습니다.';
	const singleton = '단일로 제한된 클래스({$class} use Singleton)를 더 이상 생성할 수 없습니다.';
}