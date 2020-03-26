<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerPositionTable extends Migration
{
    /**
     * Run the migrations.
     * banner 位置
     * @return void
     */
    public function up()
    {
        Schema::create('banner_position', function (Blueprint $table) {
            $table->id();
            $table->string('position_name')->comment("位置名称");
            $table->string('image_size')->comment("图片大小 长*高*宽");
            $table->string('info')->nullable()->comment("备注");
            $table->integer('is_show')->comment("状态 1显示 2隐藏");
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
        Schema::dropIfExists('banner_position');
    }
}
