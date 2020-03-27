<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     * 文章
     * @return void
     */
    public function up()
    {
        Schema::create('article', function (Blueprint $table) {
            $table->id();
            $table->string("title")->default("")->comment("标题");
            $table->string("summary",255)->default("")->comment("摘要");
            $table->string("thumb_img")->default("")->comment("缩率图");
            $table->string("admin")->default("admin")->comment("编辑者");
            $table->string("com")->default("")->comment("来源");
            $table->string("is_show")->default("1")->comment("是否展示 1展示 2不展示");
            $table->text("content")->comment("内容");
            $table->integer("hot")->default(2)->comment("是否热点 1是 2否");
            $table->integer("sort")->default(0)->comment("优先级 数字越大，排名越前");
            $table->text("thumb_img_info")->comment("缩率图相关信息");
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
        Schema::dropIfExists('article');
    }
}
