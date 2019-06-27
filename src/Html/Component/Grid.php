<?php

namespace Dot\Html\Component;

class Grid
{

    const close = '</div>';

    static function open($col = 1, $gap = null)
    {
        $gap = $gap ?
            ' dot-grid-gap' . $gap :
            '';
        return <<<HTML
<div class="dot-grid{$col} {$gap}">
HTML;
    }
}