<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SysAdminLoginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('sys_admin_login_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->comment('管理员id');
            $table->string('login_name',50)->comment('管理员名称');
            $table->string('login_role',50)->nullable()->comment('管理员角色');
            $table->string('client_ip',50)->nullable()->comment('ip');
            $table->string('browser_info',50)->nullable()->comment('登陆浏览器及版本');
            $table->string('os_info',50)->nullable()->comment('操作系统信息');
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
        Schema::dropIfExists('sys_admin_login_log');
    }
}
