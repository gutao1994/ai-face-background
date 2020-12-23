<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWxUsersTable extends Migration
{

    public function up()
    {
        Schema::create('wx_users', function (Blueprint $table) {
            $table->id();
            $table->string('openid', 255)->default('')->comment('open id');
            $table->string('nickname', 255)->default('')->comment('用户昵称');
            $table->string('avatar', 255)->default('')->comment('用户头像');
            $table->tinyInteger('sex')->unsigned()->default(0)->comment('性别，0未知 1男 2女');
            $table->string('country', 32)->default('')->comment('国家，如中国为CN');
            $table->string('province', 32)->default('')->comment('省份');
            $table->string('city', 32)->default('')->comment('城市');
            $table->string('language', 30)->default('')->comment('语言');
            $table->tinyInteger('share_permission')->unsigned()->default(0)->comment('用户是否有分享返现的权限 0没有 1有');
            $table->integer('share_commission')->unsigned()->default(0)->comment('分享返现的佣金，单位分');
            $table->integer('share_total_commission')->unsigned()->default(0)->comment('分享返现的总佣金，单位分');
            $table->integer('share_order_num')->unsigned()->default(0)->comment('分享的成交订单数');
            $table->string('remark', 255)->default('')->comment('备注');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wx_users');
    }
}
