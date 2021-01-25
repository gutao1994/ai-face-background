<?php

namespace App\Services;

use App\Services\Tools\SystemTransForm;
use Illuminate\Support\Facades\Request;

class OrderService
{

    /**
     * 生成订单号
     */
    public function genOrderNum()
    {
        $time = explode(' ', microtime());

        return SystemTransForm::ten2SixtyTwo(ip2long(Request::ip())) .
            SystemTransForm::ten2SixtyTwo((int)$time[1]) .
            SystemTransForm::ten2SixtyTwo((int)($time[0] * 1000000)) .
            SystemTransForm::ten2SixtyTwo(mt_rand(0, 9999999));
    }



}





















