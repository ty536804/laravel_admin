<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEssayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('essay', function (Blueprint $table) {
            $table->id();
            $table->integer("banner_position_id")->comment("位置");
            $table->string("essay_title")->comment("标题");
            $table->text("essay_content")->nullable(true)->comment("内容");
            $table->text("essay_img")->nullable(true)->comment("缩率图");
            $table->integer("essay_status")->default(1)->comment("状态 1显示 0隐藏");
            $table->text("essay_img_info")->nullable(true)->comment("缩率图信息");
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
        Schema::dropIfExists('essay');
    }
}
