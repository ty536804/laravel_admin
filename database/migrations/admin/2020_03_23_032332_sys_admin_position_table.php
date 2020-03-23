<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SysAdminPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('sys_admin_position', function (Blueprint $table) {
            $table->increments('id');
            $table->string('position_name',30)->nullable($value="")->comment('职位名称');
            $table->integer('department_id')->default('0')->comment("归属部门");
            $table->string('desc')->default('')->comment("职位描述");
            $table->text('powerid')->comment("职位权限");
            $table->integer('status')->default('1')->comment("职位状态 1 正常");
            $table->integer('city_id')->default(0)->comment("城市id");
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
        //
        Schema::dropIfExists('sys_admin_position');
    }
}
