<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageTable extends Migration
{
    /**
     * Run the migrations.
     * 留言
     * @return void
     */
    public function up()
    {
        Schema::create('message', function (Blueprint $table) {
            $table->id();
            $table->string("mname",100)->comment("姓名");
            $table->string("area",100)->comment("地区");
            $table->string("tel",20)->comment("电话");
            $table->text("content")->comment("留言内容");
            $table->string("com")->comment("留言来源页");
            $table->string("client")->comment("客户端");
            $table->string("ip",50)->comment("ip地址");
            $table->integer("channel")->comment("留言板块");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message');
    }
}
