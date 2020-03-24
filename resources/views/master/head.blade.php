<?php
$user =  new \App\Services\AdminUser();
$userinfo  = $user->getUser();
$uid = $user->getId();
?>
<div class="row border-bottom">
    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-info " href="#"><i class="fa fa-bars"></i> </a>
            <form role="search" class="navbar-form-custom" method="post" action="search_results.html">
                <div class="form-group">
                    <input type="text" placeholder="请输入您需要查找的内容 …" class="form-control" name="top-search" id="top-search">
                </div>
            </form>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                    <i class="fa fa-gears"></i>用户信息<span class="label label-primary"></span>
                </a>
                <ul class="dropdown-menu dropdown-alerts">
                    <li>
                        <a href="{{\Illuminate\Support\Facades\URL::action("Admin\AdminController@logout")}}">
                            <div>
                                <i class="fa fa-sign-out fa-fw"></i>退出
                            </div>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="#" data-toggle="modal" data-target="#myModal"><div>
                                <i class="fa fa-edit fa-fw"></i> 修改信息
                            </div>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="#" data-toggle="modal" data-target="#changePwd"><div>
                                <i class="fa fa-key fa-fw"></i> 修改密码
                            </div>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>
{{--修改信息弹框--}}
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">修改信息</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t" id="commentForm">
                    {{csrf_field()}}
                    <input id="id" type="hidden" class="form-control" name="id" value="{{$uid}}">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">用户名：</label>
                        <div class="col-sm-8">
                            <input id="login_name" name="login_name" minlength="2" type="text" class="form-control"  value="{{$userinfo->login_name}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">E-mail：</label>
                        <div class="col-sm-8">
                            <input id="email" type="email" class="form-control" name="email" value="{{$userinfo->email}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">电话：</label>
                        <div class="col-sm-8">
                            <input id="tel" type="text" class="form-control" name="tel" value="{{$userinfo->tel}}">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button class="btn btn-primary update_info" type="submit">提交</button>
            </div>
        </div>
    </div>
</div>
{{--修改密码弹框--}}
<div class="modal fade" id="changePwd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">修改密码</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t" id="update_pwd">
                    {{csrf_field()}}
                    <input id="id" type="hidden" class="form-control" name="id" value="{{$uid}}">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">用户名：</label>
                        <div class="col-sm-8">
                            <input id="login_name" name="login_name" minlength="2" type="text" class="form-control"  value="{{$userinfo->login_name}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">旧密码：</label>
                        <div class="col-sm-8">
                            <input id="pwd" type="password" class="form-control" name="pwd" value="**********">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">新密码：</label>
                        <div class="col-sm-8">
                            <input id="newpwd" type="password" class="form-control" name="newpwd" value="">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button class="btn btn-primary head_sub" type="submit">提交</button>
            </div>
        </div>
    </div>
</div>