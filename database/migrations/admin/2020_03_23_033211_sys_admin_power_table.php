<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SysAdminPowerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_admin_power', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pname',"50")->comment("权限名称");
            $table->integer('ptype')->default('1')->comment("1 左侧菜单 2顶部菜单");
            $table->string('icon',"50")->default('')->comment("权限ICON样式名称");
            $table->string('desc',"50")->default('')->comment("权限描述");
            $table->string('purl',100)->comment("权限地址");
            $table->integer('parent_id')->default('0')->comment("上级地址");
            $table->integer('pindex')->default('0')->comment("显示排序");
            $table->integer('status')->default('1')->comment("状态 1 显示 0不显示");
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
        Schema::dropIfExists('sys_admin_power');
    }
}
