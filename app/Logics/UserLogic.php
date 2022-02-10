<?php

namespace App\Logics;

use App\Models\WxUser;

class UserLogic
{

    /**
     * update user
     */
    public function updateUser($data)
    {
        $user = WxUser::query()->where('openid', $data['openid'])->first();

        if (empty($user)) {
            $user = new WxUser();
            $user->openid = $data['openid'];
            $user->share_permission = 1;
            $user->share_per_price = config('aiface.share_commission_price.default') * 100;
        }

        $user->nickname = $data['nickName'];
        $user->avatar = $data['avatarUrl'];
        $user->sex = $data['gender'];
        $user->country = $data['country'];
        $user->province = $data['province'];
        $user->city = $data['city'];
        $user->language = $data['language'];
        $user->save();

        return $user;
    }

    /**
     * 检查分享的用户是否正常
     */
    public function checkShareUser($userId, $shareUserId)
    {
        if (empty($shareUserId))
            return false;

        if ($userId == $shareUserId) //不能为同一个用户
            return false;

        $shareUser = WxUser::query()->find($shareUserId);

        if (empty($shareUser) || $shareUser->share_permission != 1) //用户不存在 或者 没有分享返现权限
            return false;

        return $shareUser;
    }



}

























































