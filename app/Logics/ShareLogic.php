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
        $result = ShareCommissionLog::query()
            ->where('user_id', $shareUser->id)
            ->where('lower_user_id', $lowUserId)
            ->first();

        if (empty($result)) { //之前没有分享记录
            DB::beginTransaction();

            $commissionPrice = config('aiface.share_commission_price') * 100;

            $shareUser->share_commission += $commissionPrice;
            $shareUser->share_total_commission += $commissionPrice;
            $shareUser->share_order_num += 1;
            $shareUser->save();

            ShareCommissionLog::query()->create([
                'user_id' => $shareUser->id,
                'type' => 1,
                'lower_user_id' => $lowUserId,
                'lower_user_nickname' => WxUser::query()->find($lowUserId)->nickname,
                'lower_order_id' => $order->id,
                'amount' => $commissionPrice,
            ]);

            DB::commit();
        }
    }



}








