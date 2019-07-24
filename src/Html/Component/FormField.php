<?php

namespace Dot\Html\Component;

use Dot\Html\Tag\ControlBase;

class FormField
{

    static function div($control)
    {
        $label = $control->label ?: $control->name;
        $classList = ['form-field'];
        $required = '';

        if($control->required){
            $classList[] = 'form-field_required';
            $required = '<em>필수 <span class="hide">항목 입니다.</em>' ;
        }

        $classList = implode(' ', $classList);

        return <<<HTML
            <div class="{$classList}">
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
