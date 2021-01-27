<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\ActionException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DB::listen(function ($query) {
            if (
                (stripos($query->sql, 'select') === false && stripos($query->sql, 'admin') === false) ||
                config('app.env') != 'production'
            ) {
                Log::info('查询日志：' . $query->sql . ' 参数:' . json_encode($query->bindings, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . ' 时间:' . $query->time . 'ms');
            }
        });

        app('api.exception')->register(function (ActionException $exception) {
            return response()->json([
                'action' => $exception->actionName,
                'message' => $exception->actionMessage,
            ]);
        });



    }



}


