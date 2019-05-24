<?php

namespace Dot\Text;

trait Texts {
	function _text(string $label, array $values = []){
		return Text::getText($this, $label, $values);
	}

	function _parentText(string $label, array $values = []){
		return Text::getText(\get_parent_class($this), $label, $values);
	}
}