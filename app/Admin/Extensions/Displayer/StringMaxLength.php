<?php

namespace App\Admin\Extensions\Displayer;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;

class StringMaxLength extends AbstractDisplayer
{

    public function display($len = 15)
    {
        Admin::script('$(".-string-max-length-tooltip").tooltip();');

        $val = mb_strlen($this->value) > $len ? (mb_substr($this->value, 0, $len) . '...') : $this->value;

        return <<<EOT
<a class="-string-max-length-tooltip" title="{$this->value}" style="color: black;">{$val}</a>
EOT;
    }



}






