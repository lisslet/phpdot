<?php

namespace Dot\Html\Component;

use Dot\Html\Tag\ControlBase;

class FormField
{
    static function div(ControlBase $control)
    {
        $label = $control->label ?: $control->name;
        $required = $control->required ?
            '<em>필수 <span class="hide">항목 입니다.</em>' :
            '';

        return <<<HTML
            <div class="form-field">
                <div class="form-field-pad">
                <label>
                    <span class="form-field-label">
                        {$label} {$required}
                    </span>
                    <span class="form-field-input">
                        {$control}
                    </span>
                </label>                          
                </div>
            </div>
HTML;
    }
}