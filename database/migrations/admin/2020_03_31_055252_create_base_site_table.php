<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaseSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_site', function (Blueprint $table) {
            $table->id();
            $table->string("site_title")->comment("网站标题");
            $table->text("site_desc")->comment("网站描述");
            $table->text("site_keyboard")->comment("网站关键字");
            $table->string("site_copyright")->comment("版权");
            $table->string("site_tel")->comment("电话");
            $table->string("site_email")->comment("邮箱");
            $table->string("site_address")->comment("地址");
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
        Schema::dropIfExists('base_site');
    }
}
