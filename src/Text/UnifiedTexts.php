<?php

namespace Dot\Text;

trait UnifiedTexts {
	function _text(string $label, array $values = []): string{

		return Text::getUnifiedText(
			\strchr($label, '::') ?
				$label :
				\get_class($label),
			$label,
			$values
		);
	}

	function _parentText(string $label, array $values = []): string{
		return Text::getUnifiedText(get_parent_class($this), $label, $values);
	}
}