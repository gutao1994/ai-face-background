<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use DefaultDatetimeFormat;

    protected $guarded = [];
}
