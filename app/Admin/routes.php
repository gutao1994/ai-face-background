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

    $router->get('/wx_users', 'WxUserController@index'); //用户列表
    $router->put('/wx_user/{id}/share_permission', 'WxUserController@updateSharePermission'); //更新用户的分享返现权限



});


