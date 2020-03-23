<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>{{config('app.name')}} | @yield('title')- 登录</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link href="{{asset("/admin/css/bootstrap.min.css")}}" rel="stylesheet">
    <link href="{{asset("/admin/css/font-awesome.css?v=4.4.0")}}" rel="stylesheet">
    <link href="{{asset("/admin/css/animate.css")}}" rel="stylesheet">
    <link href="{{asset("/admin/css/style.css")}}" rel="stylesheet">
    <link href="{{asset("/admin/css/login.css")}}" rel="stylesheet">
    <link href="{{asset('/admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>
        if (window.top !== window.self) {
            window.top.location = window.location;
        }
    </script>

</head>

<body class="signin">
<div class="signinpanel">
    <div class="row">
        <div class="col-sm-12">
            <form id="addform" name="addform">
                {{csrf_field()}}
                <h4 class="no-margins">登录：</h4>
                <p class="m-t-md" style="color:#666">登录到{{ config('app.name', 'Laravel') }}系统后台管理</p>
                <input type="text" class="form-control uname" placeholder="用户名" id="login_name" name="login_name"/>
                <input type="password" class="form-control pword m-b" placeholder="密码"  id="pwd" name="pwd"/>
                <a href="">忘记密码了？</a>
                <button class="btn btn-success btn-block login">登录</button>
            </form>
        </div>
    </div>
    <div class="signup-footer">
        <div class="pull-left">
            &copy; hAdmin
        </div>
    </div>
</div>

<script src="{{asset('/admin/js/jquery.min.js?v=2.1.4')}}"></script>
<script src="{{asset('/admin/js/bootstrap.min.js?v=3.3.6')}}"></script>
<script src="{{asset('/admin/js/plugins/sweetalert/sweetalert.min.js')}}"></script>
<script type="application/javascript">
    $(function () {
        $('.login').on('click', function () {
            if($.trim($('.uname').val()) == ""){
                sweetAlert("操作失败",'用户名不允许为空','error');
            }
            if($.trim($('.pword').val()) == ""){
                sweetAlert("操作失败",'用户名不允许为空','error');
            }
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{URL::action('Admin\AdminController@login')}}",
                data: $("#addform").serialize(),
                success: function (result) {
                    if (result.code == "10000") {
                        window.location.href = "{{URL::action('Admin\AdminController@index')}}";
                        if (result.data != 1){
                            window.location.href = result.data;
                        } else{
                            window.location.href = "{{URL::action('Admin\AdminController@index')}}";
                        }
                    } else {
                        console.log(result);
                        sweetAlert("登录失败",result.msg,'error');
                    }
                }
            });
            return false;
        })
    })
</script>
</body>
</html>
