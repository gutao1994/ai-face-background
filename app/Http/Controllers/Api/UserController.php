<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Requests\Api\Login;

class UserController extends ApiController
{

    /**
     * 用户登陆
     */
    public function login(Login $request)
    {
        $app = \EasyWeChat::miniProgram();
        $sessionKey = $app->auth->session($request->code);
        $decryptedData = $app->encryptor->decryptData($sessionKey, $request->iv, $request->encryptedData);

        return $this->response->array(['data' => $token]);
    }

    /**
     * 用户详情
     */
    public function userDetail()
    {

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






