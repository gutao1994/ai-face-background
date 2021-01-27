<?php

namespace App\Logics;

use App\Models\Order;
use App\Common\HandyClass;

class OrderLogic
{

    use HandyClass;

    /**
     * 按步骤检查订单，同时检查订单用户所属
     */
    public function checkStepOrder($no, $user, $status)
    {
        $order = Order::query()
            ->where('no', $no)
            ->where('user_id', $user->id)
            ->first();

        if (empty($order))
            return false;

        if ($order->status != $status)
            return false;

        return $order;
    }

    /**
     * 订单api调用失败次数+1
     */
    public function incrApiErrorCount($order, $save = true)
    {
        $order->api_error_count += 1;

        if ($order->api_error_count >= config('aiface.max_api_error_count'))
            $order->status = 70;

        if ($save) $order->save();
    }

    /**
     * 回退订单状态
     */
    public function statusRollback($order)
    {
        $order->img = '';
        $order->status = 10;
        $order->save();
    }

    /**
     * 判断出现api错误时，是否需要回退订单状态
     */
    public function isStatusRollback($httpCode, $message)
    {
        if (
            ( $httpCode == 400 && preg_match('/IMAGE_ERROR_UNSUPPORTED_FORMAT/', $message) ) ||
            ( $httpCode == 400 && $message == 'NO_FACE_FOUND' ) ||
            ( $httpCode == 400 && $message == 'INVALID_IMAGE_FACE' ) ||
            ( $httpCode == 400 && preg_match('/INVALID_IMAGE_SIZE/', $message) ) ||
            ( $httpCode == 400 && $message == 'INVALID_IMAGE_URL' ) ||
            ( $httpCode == 400 && $message == 'INVALID_IMAGE_T' ) ||
            ( $httpCode == 400 && preg_match('/IMAGE_FILE_TOO_LARGE/', $message) )
        ) return true;

        return false;
    }



}





