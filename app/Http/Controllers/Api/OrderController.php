<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Transformers\OrderTransformer;
use App\Http\Requests\Api\UploadImg;
use App\Exceptions\ActionException;
use App\Exceptions\RetryException;
use App\Exceptions\StatusRollbackException;
use Illuminate\Support\Facades\Storage;
use Dingo\Api\Exception\StoreResourceFailedException;
use GuzzleHttp\Exception\TransferException;
use Lcobucci\JWT\Parsing\Decoder;

/**
 * @property \App\Services\OrderService $orderService
 * @property \App\Logics\UserLogic $userLogic
 * @property \App\Logics\ShareLogic $shareLogic
 * @property \App\Services\FacePlusPlusService $facePlusPlusService
 * @property \App\Logics\OrderLogic $orderLogic
 * @property \App\Services\FileService $fileService
 * @property \App\Logics\FaceLogic $faceLogic
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
        try {
            $path = Storage::putFileAs('imgs', $request->img->path(), $this->fileService->genFileName($request->img));

            $order = $request->requestOrder;
            $order->img = $path;
            $order->status = 20;
            $order->save();

            return response('');
        } catch (\Exception $exception) {
            throw new StoreResourceFailedException('上传照片失败');
        }
    }

    /**
     * 面部特征分析
     */
    public function actionFacialFeatures(Request $request)
    {
        try {
            $order = $this->orderLogic->checkStepOrder($request->no, $this->user, 20);

            if ($order === false)
                throw new \Exception('订单错误');

            $response = $this->facePlusPlusService->facialfeatures(['image_url' => Storage::url($order->img)]);
            $httpCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            if ($httpCode == 200) {
                $order->facialfeatures_data = $body;
                $order->status = 30;
                $order->save();

                return response('');
            }

            $body = json_decode($body, true);

            if ($httpCode == 403 && $body['error_message'] == 'CONCURRENCY_LIMIT_EXCEEDED') //并发数超过限制
                throw new RetryException();

            if ($httpCode == 412 && $body['error_message'] == 'IMAGE_DOWNLOAD_TIMEOUT') { //下载图片超时
                $this->orderLogic->incrApiErrorCount($order);
                throw new RetryException();
            }

            if ($this->orderLogic->isStatusRollback($httpCode, $body['error_message'])) {
                $this->orderLogic->incrApiErrorCount($order, false);

                if ($order->status == 70) {
                    $order->save();
                } else {
                    $this->orderLogic->statusRollback($order);
                }

                throw new StatusRollbackException($this->facePlusPlusService->parseErrorMessage($body['error_message']));
            } else {
                throw new \Exception($this->facePlusPlusService->parseErrorMessage($body['error_message']));
            }
        } catch (TransferException $transferException) {
            throw new ResourceException('面部特征分析失败');
        } catch (ActionException $actionException) {
            throw $actionException;
        } catch (\Exception $exception) {
            throw new ResourceException($exception->getMessage());
        }
    }

    /**
     * 皮肤分析
     */
    public function actionSkinAnalyze(Request $request)
    {
        try {
            $order = $this->orderLogic->checkStepOrder($request->no, $this->user, 30);

            if ($order === false)
                throw new \Exception('订单错误');

            $response = $this->facePlusPlusService->skinanalyze(['image_url' => Storage::url($order->img)]);
            $httpCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            if ($httpCode == 200) {
                $order->skinanalyze_data = $body;
                $order->status = 40;
                $order->save();

                return response('');
            }

            $body = json_decode($body, true);

            if ($httpCode == 403 && $body['error_message'] == 'CONCURRENCY_LIMIT_EXCEEDED') //并发数超过限制
                throw new RetryException();

            if ($httpCode == 412 && $body['error_message'] == 'IMAGE_DOWNLOAD_TIMEOUT') { //下载图片超时
                $this->orderLogic->incrApiErrorCount($order);
                throw new RetryException();
            }

            if ($this->orderLogic->isStatusRollback($httpCode, $body['error_message'])) {
                $this->orderLogic->incrApiErrorCount($order, false, 4);

                if ($order->status == 70) {
                    $order->save();
                } else {
                    $this->orderLogic->statusRollback($order);
                }

                throw new StatusRollbackException($this->facePlusPlusService->parseErrorMessage($body['error_message']));
            } else {
                throw new \Exception($this->facePlusPlusService->parseErrorMessage($body['error_message']));
            }
        } catch (TransferException $transferException) {
            throw new ResourceException('皮肤分析失败');
        } catch (ActionException $actionException) {
            throw $actionException;
        } catch (\Exception $exception) {
            throw new ResourceException($exception->getMessage());
        }
    }

    /**
     * Detect
     */
    public function actionDetect(Request $request)
    {
        try {
            $order = $this->orderLogic->checkStepOrder($request->no, $this->user, 40);

            if ($order === false)
                throw new \Exception('订单错误');

            $response = $this->facePlusPlusService->detect([
                'image_url' => Storage::url($order->img),
                'return_attributes' => 'gender,age,smiling,headpose,facequality,blur,eyestatus,emotion,beauty,mouthstatus,eyegaze,skinstatus',
            ]);
            $httpCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            if ($httpCode == 200) {
                $order->detect_data = $body;
                $order->status = 50;
                $order->save();

                return response('');
            }

            $body = json_decode($body, true);

            if ($httpCode == 403 && $body['error_message'] == 'CONCURRENCY_LIMIT_EXCEEDED') //并发数超过限制
                throw new RetryException();

            if ($httpCode == 412 && $body['error_message'] == 'IMAGE_DOWNLOAD_TIMEOUT') { //下载图片超时
                $this->orderLogic->incrApiErrorCount($order);
                throw new RetryException();
            }

            throw new \Exception($this->facePlusPlusService->parseErrorMessage($body['error_message']));
        } catch (TransferException $transferException) {
            throw new ResourceException('人脸综合分析失败');
        } catch (ActionException $actionException) {
            throw $actionException;
        } catch (\Exception $exception) {
            throw new ResourceException($exception->getMessage());
        }
    }

    /**
     * 获取面相分析结果
     */
    public function actionResult(Request $request)
    {
        try {
            $order = $this->orderLogic->checkStepOrder($request->no, $this->user, 50);

            if ($order === false)
                throw new \Exception('订单错误');

            $order->face_result = $this->faceLogic->analyse($order);
            $order->status = 60;
            $order->save();

            return response('');
        } catch (\Exception $exception) {
            throw new ResourceException($exception->getMessage());
        }
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
     * 来自分享的订单详情
     */
    public function orderDetailFromShare(Decoder $decoder, Request $request)
    {
        try {
            $shareToken = $request->input('share_token', '');
            $shareToken = $decoder->base64UrlDecode($shareToken);
            $shareTokenArr = explode('.', $shareToken);

            if (count($shareTokenArr) != 2)
                throw new \Exception('解码出错');

            $userId = $decoder->base64UrlDecode($shareTokenArr[0]);
            $orderNo = $decoder->base64UrlDecode($shareTokenArr[1]);

            $order = Order::query()->where('user_id', $userId)->where('no', $orderNo)->first();

            if (empty($order))
                throw new \Exception('订单不存在');

            return $this->response->item($order, new OrderTransformer());
        } catch (\Exception $exception) {
            throw new ResourceException($exception->getMessage());
        }
    }

    /**
     * 订单列表
     */
    public function orderList(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 20);

        $orders = Order::query()
            ->select(['id', 'no', 'img', 'status', 'created_at'])
            ->where('user_id', $this->user->id)
            ->orderByDesc('id')
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        return $this->response->collection($orders, new OrderTransformer());
    }



}














