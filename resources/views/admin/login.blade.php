<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>后台登录 - {{ config('app.name', '易学教育') }}</title>
    <meta name="keywords" content="后台登录">
    <meta name="description" content="后台登录">
    <link href="{{asset('/admin/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/admin/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('/admin/css/animate.css')}}" rel="stylesheet">
    <link href="{{asset('/admin/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('/admin/css/login.css')}}" rel="stylesheet">
    <link href="{{asset('/admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
    <script>
        if(window.top!==window.self){window.top.location=window.location};
    </script>

</head>

<body class="signin">
<div class="signinpanel">
    <div class="row">
        <div class="col-sm-7 animated fadeInLeft">
        </div>
        <div class="col-sm-5 animated fadeInRight">
            <form id="addform" name="addform">
                {{csrf_field()}}
                <p class="login-title">登录</p>
                <p class="m-t-md" style="color:#666">登录到{{ config('app.name', '易学教育') }}系统后台管理</p>
                <input type="text" class="form-control uname" name="login_name" value="{{old('name')}}" required placeholder="用户名" />
                <input type="password" class="form-control pword m-b" name="pwd" required placeholder="密码" />
                <p></p>
                <button class="btn btn-success btn-block" id="loginButton" name="loginButton">登录</button>
{{--                <a href="">注册</a>--}}
            </form>
        </div>
    </div>
    <div class="signup-footer animated fadeInUp">
        &copy; {{ config('app.name', '易学教育') }}
    </div>
</div>
<script src="{{asset('/admin/js/jquery.min.js?v=2.1.4')}}"></script>
<script src="{{asset('/admin/js/bootstrap.min.js?v=3.3.6')}}"></script>
<script src="{{asset('/admin/js/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script type="application/javascript">
    $(function () {
        $('#loginButton').on('click', function () {
            if ($.trim($(".uname").val()) == "") {
                sweetAlert("登录失败","用户名不能为空",'error');
                return false;
            }

            if ($.trim($(".pword").val()) == "") {
                sweetAlert("登录失败","密码不能为空",'error');
                return false;
            }
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{URL::action('Admin\AdminController@login')}}",
                data: $("#addform").serialize(),
                success: function (result) {
                    if (result.code == "10000") {
                        window.location.href = "{{URL::action('Admin\AdminController@index')}}";
                    } else {
                        sweetAlert("登录失败",result.msg,'error');
                    }
                },
                error: function (result) {
                    $.each(result.responseJSON, function (k, val) {
                        sweetAlert("登录失败",val[0],'error');
                        return false;
                    });
                }
            });
            return false;
        });
    });
</script>
</body>
</html>
