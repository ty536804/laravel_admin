<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SysMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_media', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->default('0')->comment('所有者');
            $table->integer('m_type')->default('10000')->comment('媒体文件分类');
            $table->string('m_format',50)->default('image/jpeg')->comment('媒体文件格式');
            $table->string('m_name',100)->comment('媒体文件分类原名');
            $table->string('m_url',150)->comment('媒体文件存储地址');
            $table->integer('m_size')->default('0')->comment('文件大小');
            $table->integer('m_width')->default('0')->comment('文件宽度');
            $table->integer('m_height')->default('0')->comment('文件高度');
            $table->integer('m_qiniu')->default('0')->comment('七牛文件同步状态');
            $table->text('m_metadata')->comment('文件原始数据');
            $table->integer('m_status')->default('1')->comment('文件状态');
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
        Schema::dropIfExists('sys_media');
    }
}
