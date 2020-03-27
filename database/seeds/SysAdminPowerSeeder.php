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
["id"=>5,"pname"=>"管理员","ptype"=>1,"icon"=>"#","desc"=>null,"purl"=>"Admin\\UserController@list","parent_id"=>1,"pindex"=>1,"status"=>1,"created_at"=>"2018-06-24 11:37:19","updated_at"=>"2018-08-03 07:32:14"],
["id"=>6,"pname"=>"Banner管理","ptype"=>1,"icon"=>"fa-picture-o","desc"=>null,"purl"=>"#","parent_id"=>0,"pindex"=>1,"status"=>1,"created_at"=>"2020-03-25 07:27:33","updated_at"=>"2020-03-26 07:15:10"],
["id"=>7,"pname"=>"banner列表","ptype"=>1,"icon"=>"#","desc"=>null,"purl"=>"Backend\\BannerController@index","parent_id"=>6,"pindex"=>99,"status"=>1,"created_at"=>"2020-03-25 07:41:28","updated_at"=>"2020-03-26 07:14:50"],
["id"=>8,"pname"=>"banner展示位置","ptype"=>1,"icon"=>"fa fa-telegram","desc"=>null,"purl"=>"Backend\\BannerController@positionList","parent_id"=>6,"pindex"=>99,"status"=>1,"created_at"=>"2020-03-25 09:41:56","updated_at"=>"2020-03-26 07:13:15"],
["id"=>9,"pname"=>"新闻管理","ptype"=>1,"icon"=>"fa-newspaper-o","desc"=>null,"purl"=>"#","parent_id"=>0,"pindex"=>3,"status"=>1,"created_at"=>"2020-03-26 09:29:40","updated_at"=>"2020-03-26 09:32:00"],
["id"=>10,"pname"=>"新闻列表","ptype"=>1,"icon"=>"#","desc"=>null,"purl"=>"Backend\\ArticleController@show","parent_id"=>9,"pindex"=>99,"status"=>1,"created_at"=>"2020-03-26 09:30:21","updated_at"=>"2020-03-26 09:30:21"],
["id"=>11,"pname"=>"留言列表","ptype"=>1,"icon"=>"fa-hacker-news","desc"=>null,"purl"=>"#","parent_id"=>0,"pindex"=>4,"status"=>1,"created_at"=>"2020-03-26 09:32:14","updated_at"=>"2020-03-26 09:32:14"],
["id"=>12,"pname"=>"留言列表","ptype"=>1,"icon"=>"#","desc"=>null,"purl"=>"Backend\\MessageController@show","parent_id"=>11,"pindex"=>99,"status"=>1,"created_at"=>"2020-03-26 09:32:41","updated_at"=>"2020-03-26 09:32:41"]]
        );
    }
}//Created at 2020-03-26 09:51:04