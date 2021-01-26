<?php

namespace App\Logics;

use App\Models\Order;

class OrderLogic
{

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



}





