<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Requests\Api\Login;
use Tymon\JWTAuth\JWTAuth;
use App\Models\Order;
use App\Models\ShareCommissionLog;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Support\Facades\DB;

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
        $lastRecord = ShareCommissionLog::query()
            ->where('user_id', $this->user->id)
            ->where('type', 2)
            ->where('cashout_status', 1)
            ->first();

        return $this->response->array([
            'share_commission' => $this->user->share_commission,
            'cashout_ing' => $lastRecord ? $lastRecord->amount : 0,
            'share_order_num' => $this->user->share_order_num,
        ]);
    }

    /**
     * 分享佣金记录
     */
    public function commissionLog(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 20);

        $logs = ShareCommissionLog::query()
            ->where('user_id', $this->user->id)
            ->orderByDesc('id')
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        return $this->response->array($logs->toArray());
    }

    /**
     * 申请佣金提现
     */
    public function commissionCashout(Request $request)
    {
        try {
            $amount = (string)$request->input('amount', 0);

            if (!preg_match('/^[1-9]+0*00$/U', $amount))
                throw new \Exception('不合法的金额');

            if ($amount > $this->user->share_commission)
                throw new \Exception('佣金余额不足');

            DB::beginTransaction();

            $this->user->share_commission -= $amount;
            $this->user->save();

            ShareCommissionLog::query()->create([
                'user_id' => $this->user->id,
                'type' => 2,
                'amount' => $amount,
                'cashout_status' => 1,
            ]);

            DB::commit();

            return response('');
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new ResourceException($exception->getMessage());
        }
    }



}




















