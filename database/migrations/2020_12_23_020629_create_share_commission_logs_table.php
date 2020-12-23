<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShareCommissionLogsTable extends Migration
{

    public function up()
    {
        Schema::create('share_commission_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->default(0)->comment('分享者的用户id');
            $table->tinyInteger('type')->unsigned()->default(0)->comment('记录类型 1得到分享佣金 2提现分享佣金');
            $table->integer('lower_user_id')->unsigned()->default(0)->comment('被分享的用户id');
            $table->string('lower_user_nickname', 255)->default('')->comment('被分享的用户昵称');
            $table->integer('lower_order_id')->unsigned()->default(0)->comment('被分享的订单id');
            $table->integer('amount')->unsigned()->default(0)->comment('金额');
            $table->tinyInteger('cashout_status')->unsigned()->default(0)->comment('提现状态 1提现中 2提现成功 3提现失败');
            $table->text('cashout_remark')->nullable()->comment('提现备注');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('share_commission_logs');
    }
}
