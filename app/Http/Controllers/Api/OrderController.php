<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

/**
 * @property \App\Services\OrderService $orderService
 */
class OrderController extends ApiController
{

    /**
     * 生成订单微信预支付信息
     */
    public function wxPrepay(Request $request)
    {
        try {
            $app = \EasyWeChat::payment();

            $result = $app->order->unify([
                'body' => '支付2元开始看面相',
                'out_trade_no' => $this->orderService->genOrderNum(),
                'total_fee' => config('aiface.order_price') * 100,
                'spbill_create_ip' => $request->ip(),
                'notify_url' => url('api/order/pay/wx/prepay/notify'),
                'trade_type' => 'JSAPI',
                'openid' => $this->user->openid,
            ]);

            if (
                $result['return_code'] !== 'SUCCESS' ||
                $result['result_code'] !== 'SUCCESS'
            ) throw new \Exception('请求支付失败');

            $res = $app->jssdk->bridgeConfig($result['prepay_id'], false);

            return $this->response->array($res);
        } catch (\Exception $exception) {
            $this->response->errorInternal();
        }
    }

    /**
     * 微信支付-异步回调通知
     */
    public function wxPrepayNotify()
    {

    }

    /**
     * 上传头像图片
     */
    public function actionImg()
    {

    }

    /**
     * 面部特征分析
     */
    public function actionFacialFeatures()
    {

    }

    /**
     * 皮肤分析
     */
    public function actionSkinAnalyze()
    {

    }

    /**
     * Detect
     */
    public function actionDetect()
    {

    }

    /**
     * 获取面相分析结果
     */
    public function actionResult()
    {

    }

    /**
     * 订单详情
     */
    public function orderDetail()
    {

    }

    /**
     * 订单列表
     */
    public function orderList()
    {

    }



}














