<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SysAdminUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('sys_admin_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string("login_name",30)->comment("账号");
            $table->string("nick_name",30)->comment("姓名");
            $table->string("email",50)->comment("邮箱");
            $table->string("tel",50)->comment("电话");
            $table->string('pwd',50)->comment("密码");
            $table->string("avatr","100")->nullable($value=true)->comment("用户头像");
            $table->integer('department_id')->comment("部门");
            $table->string('position_id',150)->nullable()->comment("职位 角色");
            $table->text('city_id')->comment("城市id");
            $table->integer('status')->default('1')->comment("状态 1 正常 -1 锁定");
            $table->integer('project_id')->default('0')->comment("归属项目 0系统");
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
        Schema::dropIfExists('sys_admin_user');
    }
}
