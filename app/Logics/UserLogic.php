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
        $user = WxUser::query()->where('openid', $data['openId'])->first();

        if (empty($user)) {
            $user = new WxUser();
            $user->openid = $data['openId'];
            $user->share_permission = 1;
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



}

























































