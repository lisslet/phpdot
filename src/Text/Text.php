<?php
namespace Dot\Text;

abstract class Text {
	const LANGUAGE = 'kr';

	static function getUnifiedText(string $className, string $label, array $values = []): string{
		$textClass = getUnifiedTextClass($className);
		return getTextValue($textClass, $label, $values);
	}

	static function getText(string $parentClassName, string $label, array $values = []): string{
		$textClass = getTextClass($parentClassName);
		return getTextValue($textClass, $label, $values);
	}

	/**
	 * get text from text class
	 * @param string $from the __NAMESPACE__
	 * @param string $label the constant name
	 * @param array $values the replaceable map
	 * @return mixed|string
	 */
	static function get(string $from, string $label, array $values = []){
		return getTextValue(
			$from . '\\Text\\' . Text::LANGUAGE,
			$label,
			$values
		);
	}
}

function getUnifiedTextClass(string $className){
	$lastBackslash = \strrpos($className, '\\');
	$namespace = '\\' . \substr($className, 0, $lastBackslash);
	$namespace .= '\\Text\\';

	$textClass = $namespace . Text::LANGUAGE;
	$textClass.= substr($className, $lastBackslash);

	return $textClass;
}

function getTextClass(string $className){
	$lastBackslash = \strrpos($className, '\\');
	$namespace = '\\' . \substr($className, 0, $lastBackslash);
	$namespace .= '\\Text\\';

	$textClass = $namespace . Text::LANGUAGE;

	return $textClass;
}

function getTextValue(string $textClass, string $label, array $values = []){
	$constName = $textClass . '::' . $label;
	if(\defined($constName)){
		$text = \constant($constName);
		if($values){
			foreach($values as $key => $value){
				$text = \str_replace('{$'.$key.'}', $value, $text);
			}
		}
	}else{
		$text = $textClass . '::' . $label;
	}

	return $text;
}