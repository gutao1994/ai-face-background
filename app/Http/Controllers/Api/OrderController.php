<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Transformers\OrderTransformer;
use App\Http\Requests\Api\UploadImg;

/**
 * @property \App\Services\OrderService $orderService
 * @property \App\Logics\UserLogic $userLogic
 * @property \App\Logics\ShareLogic $shareLogic
 * @property \App\Services\FacePlusPlusService $facePlusPlusService
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

            $outTradeNo = $this->orderService->genOrderNum();

            $result = $app->order->unify([
                'body' => '支付2元开始看面相',
                'out_trade_no' => $outTradeNo,
                'total_fee' => config('aiface.order_price') * 100,
                'spbill_create_ip' => $request->ip(),
                'notify_url' => url('api/order/pay/wx/prepay/notify'),
                'trade_type' => 'JSAPI',
                'openid' => $this->user->openid,
                'attach' => json_encode([
                    'user_id' => $this->user->id,
                    'share_user_id' => $request->input('share_user_id', 0),
                ]),
            ]);

            if (
                $result['return_code'] !== 'SUCCESS' ||
                $result['result_code'] !== 'SUCCESS'
            ) throw new \Exception('请求支付失败');

            $res = $app->jssdk->bridgeConfig($result['prepay_id'], false);

            return $this->response->array($res + ['no' => $outTradeNo]);
        } catch (\Exception $exception) {
            $this->response->errorInternal();
        }
    }

    /**
     * 微信支付-异步回调通知
     */
    public function wxPrepayNotify()
    {
        $app = \EasyWeChat::payment();

        $response = $app->handlePaidNotify(function ($message, $fail) {
            if ($message['return_code'] === 'SUCCESS') { //此字段是通信标识

                if ($message['result_code'] === 'SUCCESS') { //业务结果，支付成功

                    $order = Order::query()->where('no', $message['out_trade_no'])->first();

                    if (!empty($order)) return true;

                    $attach = json_decode($message['attach'], true);
                    $shareUser = $this->userLogic->checkShareUser($attach['user_id'], $attach['share_user_id']);

                    $order = Order::query()->create([
                        'no' => $message['out_trade_no'],
                        'user_id' => $attach['user_id'],
                        'share_user_id' => $shareUser ? $shareUser->id : 0,
                        'amount' => $message['total_fee'],
                        'status' => 10,
                    ]);

                    if ($shareUser)
                        $this->shareLogic->shareCommission($shareUser, $attach['user_id'], $order);
                }

                return true;
            } else {
                return $fail('通信失败，请稍后再通知我');
            }
        });

        return $response;
    }

    /**
     * 上传头像图片
     */
    public function actionImg(UploadImg $request)
    {
//        $this->facePlusPlusService->facialfeatures([
//            'image_file' =>
//        ]);
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
    public function orderDetail(Request $request)
    {
        $id = $request->input('id', '');
        $no = $request->input('no', '');

        if ($id) {
            $order = Order::query()->where('user_id', $this->user->id)->where('id', $id)->first();
        } elseif ($no) {
            $order = Order::query()->where('user_id', $this->user->id)->where('no', $no)->first();
        } else {
            throw new ResourceException('不合法的查询');
        }

        return $this->response->item($order, new OrderTransformer());
    }

    /**
     * 订单列表
     */
    public function orderList()
    {

    }



}














