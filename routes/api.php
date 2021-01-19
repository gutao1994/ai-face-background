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

        $api->post('order/pay/wx/prepay', 'OrderController@wxPrepay'); //生成订单微信预支付信息



    });



});


Route::group([
    'namespace' => 'App\Http\Controllers\Api',
], function (\Illuminate\Routing\Router $router) {

    $router->post('order/pay/wx/prepay/notify', 'OrderController@wxPrepayNotify'); //微信支付-异步回调通知



});



















