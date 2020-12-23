<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{

    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('no', 64)->default('')->comment('订单号');
            $table->integer('user_id')->unsigned()->default(0)->comment('用户id');
            $table->integer('share_user_id')->unsigned()->default(0)->comment('分享者用户id');
            $table->integer('amount')->unsigned()->default(0)->comment('支付的金额，单位分');
            $table->string('img', 255)->default('')->comment('用户上传的头像url');
            $table->text('facialfeatures_data')->nullable()->comment('面部特征分析API返回的数据');
            $table->text('skinanalyze')->nullable()->comment('皮肤分析API返回的数据');
            $table->text('detect_data')->nullable()->comment('Detect API返回的数据');
            $table->text('thousandlandmark_data')->nullable()->comment('稠密关键点API返回的数据');
            $table->tinyInteger('api_step_status')->unsigned()->default(0)->comment('api调用进度');
            $table->tinyInteger('api_error_count')->unsigned()->default(0)->comment('api调用错误次数');
            $table->tinyInteger('status')->unsigned()->default(0)->comment('订单状态 1未完成 2已完成 3失败');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
