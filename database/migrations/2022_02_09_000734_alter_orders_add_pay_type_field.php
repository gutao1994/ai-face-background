<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrdersAddPayTypeField extends Migration
{

    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->tinyInteger('pay_type')->unsigned()->default(0)->comment('订单支付类型 1支付钱 2看广告')->after('status');
        });
    }

    public function down()
    {
        //nothing
    }
}
