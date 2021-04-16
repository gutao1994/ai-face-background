<?php

namespace App\Admin\Extensions\Show;

use Encore\Admin\Show\AbstractField;
use Illuminate\Support\Str;
use Encore\Admin\Admin;

class Zoom extends AbstractField
{

    public $escape = false;

    public function render($arg = '')
    {
        if ($this->value) {
            $class = Str::random(10);

            Admin::script(<<<SCRIPT
new Zooming({
    enableGrab: true,
    scaleExtra: 0,
    zIndex: 10000
}).listen('.{$class}');
SCRIPT);

            return "<img src='{$this->value}' class='{$class}' style='max-width: 200px; max-height: 200px;'/>";
        } else {
            return "";
        }
    }



}




