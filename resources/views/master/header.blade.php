<?php
$user =  new \App\Services\AdminUser();
$userinfo  = $user->getUser();
$uid = $user->getId();
if(!empty($userinfo)){
?>
<header class="main-header">
    <!-- Logo -->
    <a href="/admin/index" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>易学教育</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">易学教育</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <!-- 暂未启用已经删除-->
                <!-- Notifications: style can be found in dropdown.less -->
                <!-- 暂未启用已经删除-->
                <!-- Tasks: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        @if(empty($userinfo->m_url))
                            <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="user-image" alt="User Image">
                            @else
                            <img src="{!! uploadfile() !!}{{ $userinfo->m_url }}" class="user-image" alt="User Image">
                        @endif

                        <span class="hidden-xs">{{ $userinfo->position->position_name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            @if(empty($userinfo->m_url))
                                 <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">
                            @else
                                <img src="{!! uploadfile() !!}{{ $userinfo->m_url  }}" class="img-circle" alt="User Image">
                            @endif
                            <p>
                                {{ $userinfo->nick_name ?? "张三" }} - {{ $userinfo->department->dp_name }}- {{ $userinfo->position->position_name }}
                                <small> {{$userinfo->created_at}} </small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="col-xs-4">
                                <a href="#" data-toggle="modal" data-target="#changePwd"  class="btn btn-default btn-flat">修改密码</a>
                            </div>
                            <div class="col-xs-4">
                                <a href="#" data-toggle="modal" data-target="#myModal"  class="btn btn-default btn-flat">修改信息</a>
                            </div>
                            <div class="col-xs-4">
                                <a href="{{\Illuminate\Support\Facades\URL::action('Admin\AdminController@logout')}}" class="btn btn-default btn-flat">退出</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
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
<?php
}
?>