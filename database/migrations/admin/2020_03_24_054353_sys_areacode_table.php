<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SysAreacodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_areacode', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('aid')->comment('区域编号');
            $table->integer('a_level')->default(0)->comment('节点等级 1 省 2市 3区');
            $table->integer('gaode_id')->default(0)->comment('高德ID');
            $table->string('aname', 100)->comment('区域名称');
            $table->integer('parent_id')->default(0)->comment('父节点');
            $table->integer('a_status')->default(1)->comment('1 有效 0 无效');
            $table->integer('root_id')->default(0)->comment('根节点ID');
            $table->integer('is_show')->default(1)->comment('1 前端显示 0 不显示');
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
        Schema::dropIfExists('sys_areacode', function (Blueprint $table) {
            //
        });
    }
}
