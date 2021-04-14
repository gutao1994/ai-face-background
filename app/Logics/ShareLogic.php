<?php

namespace App\Logics;

use App\Models\ShareCommissionLog;
use Illuminate\Support\Facades\DB;
use App\Models\WxUser;

class ShareLogic
{

    /**
     * 获取分享佣金逻辑
     */
    public function shareCommission($shareUser, $lowUserId, $order)
    {
        DB::beginTransaction();

        $shareUser->share_commission += $shareUser->share_per_price;
        $shareUser->share_total_commission += $shareUser->share_per_price;
        $shareUser->share_order_num += 1;
        $shareUser->save();

        ShareCommissionLog::query()->create([
            'user_id' => $shareUser->id,
            'type' => 1,
            'lower_user_id' => $lowUserId,
            'lower_user_nickname' => WxUser::query()->find($lowUserId)->nickname,
            'lower_order_id' => $order->id,
            'amount' => $shareUser->share_per_price,
        ]);

        DB::commit();
    }



}








