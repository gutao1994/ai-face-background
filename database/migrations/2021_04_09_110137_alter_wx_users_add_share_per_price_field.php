<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterWxUsersAddSharePerPriceField extends Migration
{

    public function up()
    {
        Schema::table('wx_users', function (Blueprint $table) {
            $table->smallInteger('share_per_price')->unsigned()->default(0)->comment('每单返现金额，单位分')->after('share_permission');
        });
    }

    public function down()
    {
        //nothing
    }
}
