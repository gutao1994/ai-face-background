<?php

namespace App\Admin\Extensions\Displayer;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;

class Money extends AbstractDisplayer
{

    public function display()
    {
        return round($this->value / 100, 2) . '元';
    }



}




