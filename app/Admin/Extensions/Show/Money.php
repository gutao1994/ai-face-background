<?php

namespace App\Admin\Extensions\Show;

use Encore\Admin\Show\AbstractField;

class Money extends AbstractField
{

    public function render($arg = '')
    {
        return round($this->value / 100, 2) . '元';
    }



}





