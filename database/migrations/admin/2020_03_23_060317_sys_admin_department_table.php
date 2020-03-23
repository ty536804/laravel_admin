<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SysAdminDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_admin_department', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dp_name',30)->nullable($value="")->comment('部门名称');
            $table->integer('parent_id')->default('0')->comment("父部门");
            $table->integer('root_id')->default('0')->comment("根部门");
            $table->integer('level')->default('1')->comment("部门等级");
            $table->string('path')->default('|')->comment("部门归属");
            $table->text('powerid')->comment("部门权限");
            $table->integer('status')->default('1')->comment("部门状态 1 正常");
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
        Schema::dropIfExists('sys_admin_department');
    }
}
