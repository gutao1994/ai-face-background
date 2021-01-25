<?php
declare(strict_types=1);

namespace App\Services\Tools;

use Illuminate\Support\Str;

class PregMatch
{

    /**
     * 匹配手机号码
     */
    public static function tel($val)
    {
        return preg_match('/^1[356789]\d{9}$/', (string)$val);
    }

    /**
     * 是否是从1开始的整数
     */
    public static function isOneStartInt($val)
    {
        return preg_match('/^[1-9]\d{0,}$/', (string)$val);
    }



}












