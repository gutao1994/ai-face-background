<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

$api = app()->make(\Dingo\Api\Routing\Router::class);

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
], function ($api) {

    $api->post('login', 'UserController@login'); //用户登陆

    $api->group(['middleware' => 'api.auth'], function ($api) {

        $api->post('order/pay/by_ad', 'OrderController@payByAd'); //通过看广告生成的订单

        $api->post('order/pay/wx/prepay', 'OrderController@wxPrepay'); //生成订单微信预支付信息

        $api->post('order/action/img', 'OrderController@actionImg'); //上传头像图片

        $api->post('order/action/skinanalyze', 'OrderController@actionSkinAnalyze'); //皮肤分析

        $api->post('order/action/facialfeatures', 'OrderController@actionFacialFeatures'); //面部特征分析

        $api->post('order/action/detect', 'OrderController@actionDetect'); //Detect

        $api->post('order/action/result', 'OrderController@actionResult'); //获取面相分析结果

        $api->get('order/detail', 'OrderController@orderDetail'); //订单详情

        $api->get('order/list', 'OrderController@orderList'); //订单列表

        $api->delete('order', 'OrderController@destroy'); //删除订单

        $api->get('user/detail', 'UserController@userDetail'); //用户详情

        $api->get('user/commission/detail', 'UserController@commissionDetail'); //分享佣金详情

        $api->get('user/commission/log', 'UserController@commissionLog'); //分享佣金记录

        $api->post('user/commission/cashout', 'UserController@commissionCashout'); //申请佣金提现

    });

    $api->get('order/detail/from_share', 'OrderController@orderDetailFromShare'); //来自分享的订单详情

});


Route::group([
    'namespace' => 'App\Http\Controllers\Api',
], function (\Illuminate\Routing\Router $router) {

    $router->post('order/pay/wx/prepay/notify', 'OrderController@wxPrepayNotify'); //微信支付-异步回调通知



});



















