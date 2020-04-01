<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBannerPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table("banner_position",function (Blueprint $table){
           $table->string("base_url")->default("#")->comment("跳转地址")->after("position_name");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table("banner_position", function (Blueprint $table) {
            $table->dropColumn("base_url");
        });
    }
}
