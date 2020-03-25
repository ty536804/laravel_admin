<?php
use Illuminate\Database\Seeder;
class SysAdminPowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Base\BaseSysAdminPower::truncate();
        \App\Models\Base\BaseSysAdminPower::insert(
         [["id"=>1,"pname"=>"管理菜单","ptype"=>1,"icon"=>"fa-gears","desc"=>null,"purl"=>"#","parent_id"=>0,"pindex"=>1,"status"=>1,"created_at"=>"2018-06-22 10:06:52","updated_at"=>"2018-07-12 07:27:28"],
["id"=>2,"pname"=>"权限","ptype"=>1,"icon"=>"#","desc"=>null,"purl"=>"Admin\\PowerController@list","parent_id"=>1,"pindex"=>1,"status"=>1,"created_at"=>"2018-06-22 10:50:01","updated_at"=>"2018-08-03 07:32:27"],
["id"=>3,"pname"=>"部门","ptype"=>1,"icon"=>"#","desc"=>null,"purl"=>"Admin\\DepartmentController@list","parent_id"=>1,"pindex"=>1,"status"=>1,"created_at"=>"2018-06-24 11:35:06","updated_at"=>"2018-06-24 11:35:06"],
["id"=>4,"pname"=>"职位","ptype"=>1,"icon"=>"#","desc"=>null,"purl"=>"Admin\\PositionController@list","parent_id"=>1,"pindex"=>1,"status"=>1,"created_at"=>"2018-06-24 11:37:08","updated_at"=>"2018-06-24 11:37:08"],
["id"=>5,"pname"=>"管理员","ptype"=>1,"icon"=>"#","desc"=>null,"purl"=>"Admin\\UserController@list","parent_id"=>1,"pindex"=>1,"status"=>1,"created_at"=>"2018-06-24 11:37:19","updated_at"=>"2018-08-03 07:32:14"]]
        );
    }
}//Created at 2020-03-25 07:01:57