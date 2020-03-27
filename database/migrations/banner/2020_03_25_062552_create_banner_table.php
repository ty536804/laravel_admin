<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerTable extends Migration
{
    /**
     * Run the migrations.
     * banner
     * @return void
     */
    public function up()
    {
        Schema::create('banner', function (Blueprint $table) {
            $table->id();
            $table->string('province')->default(0)->comment("省");
            $table->string('city')->default(0)->comment("市");
            $table->string('area',150)->default("")->comment("区");
            $table->string('bname',50)->comment("名称");
            $table->integer("bposition")->comment("位置 1 首页");
            $table->string("imgurl",150)->comment("图片地址");
            $table->string('target_link',150)->default("#")->comment("跳转链接");
            $table->string('begin_time',50)->nullable(true)->comment("显示开始时间");
            $table->string('end_time',50)->nullable(true)->comment("显示结束时间");
            $table->integer('is_show')->default(1)->comment("状态 1显示 2隐藏");
            $table->string('image_size')->nullable(true)->comment("图片大小 长*高*宽");
            $table->string('info')->nullable(true)->default("")->comment("备注");
            $table->text('img_info')->comment("图片详细");
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
        Schema::dropIfExists('banner');
    }
}
