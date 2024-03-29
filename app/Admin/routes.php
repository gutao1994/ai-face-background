<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->resource('wx_users', 'WxUserController');

    $router->get('wx_users/{user_id}/share/mini_program/code/{size}', 'WxUserController@miniProgramCode');

    $router->resource('order', 'OrderController');

    $router->resource('share_commission_log', 'ShareCommissionLogController');



});




