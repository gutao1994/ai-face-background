<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Requests\Api\Login;
use Tymon\JWTAuth\JWTAuth;
use App\Models\Order;

/**
 * @property \App\Logics\UserLogic $userLogic
 */
class UserController extends ApiController
{

    /**
     * 用户登陆
     */
    public function login(Login $request, JWTAuth $auth)
    {
        try {
            $app = \EasyWeChat::miniProgram();

            $sessionKey = $app->auth->session($request->code);
            $decryptedData = $app->encryptor->decryptData($sessionKey['session_key'], $request->iv, $request->encryptedData);

            $user = $this->userLogic->updateUser($decryptedData);

            return $this->response->array(['token' => $auth->fromUser($user)]);
        } catch (\Exception $exception) {
            $this->response->errorInternal();
        }
    }

    /**
     * 用户详情
     */
    public function userDetail()
    {
        return $this->response->array([
            'order_count' => Order::query()->where('user_id', $this->user->id)->count(),
        ]);
    }

    /**
     * 分享佣金详情
     */
    public function commissionDetail()
    {

    }

    /**
     * 分享佣金记录
     */
    public function commissionLog()
    {

    }



}






