<?php
use Illuminate\Database\Seeder;
class SysAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Base\BaseSysAdminUser::truncate();
        \App\Models\Base\BaseSysAdminUser::insert(
         [["id"=>1,"login_name"=>"admin","nick_name"=>"admin","email"=>"2127721@qq.coms","tel"=>"17000000000","pwd"=>"e10adc3949ba59abbe56e057f20f883e","avatr"=>"","department_id"=>1,"position_id"=>"2","city_id"=>"10000","status"=>1,"project_id"=>0,"created_at"=>"2020-03-24 02:38:50","updated_at"=>"2020-03-24 02:38:50"]]
        );
    }
}//Created at 2020-03-24 06:20:03