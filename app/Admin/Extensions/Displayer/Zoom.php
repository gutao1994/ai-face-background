<?php

namespace App\Admin\Extensions\Displayer;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;
use Illuminate\Support\Str;

class Zoom extends AbstractDisplayer
{

    public function display($width = 50, $height = 40)
    {
        if ($this->value) {
            $class = 'zoom' . Str::random(15);

            Admin::script(<<<SCRIPT
new Zooming({
    enableGrab: true,
    scaleExtra: 0,
    zIndex: 1000000,
    onOpen: function (target) {
        target.style.position = 'absolute';
    },
    onRelease: function (target) {
        target.style.position = 'absolute';
    }
}).listen('.{$class}');
SCRIPT);

            return "<img src='{$this->value}' class='{$class}' style='max-width:{$width}px;max-height:{$height}px;' />";
        } else {
            return "";
        }
    }



}




