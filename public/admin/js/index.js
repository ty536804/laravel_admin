$(function(){
    //菜单点击
    $(".J_menuItem").on('click',function(){
        var url = $(this).attr('href');
        $("#J_iframe").attr('src',url);
        return false;
    });

    //更改密码
    $('.head_sub').on('click',function () {
        if ($.trim($('#update_pwd #login_name').val()) == "") {
            sweetAlert("操作失败",'用户名不允许为空','error');
        }

        if ($.trim($('#update_pwd #pwd').val()) == "") {
            sweetAlert("操作失败",'旧密码不允许为空','error');
        }
        if ($.trim($('#update_pwd #newpwd').val()) == "") {
            sweetAlert("操作失败",'新密码不允许为空','error');
        }

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{URL::action('Admin\UserController@update')}}",
            data: $("#update_pwd").serialize(),
            success: function (result) {
                if (result.code == "10000") {
                    window.location.href = "{{URL::action('Admin\AUserController@index')}}";
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


    //更新用户信息
    $('.update_info').on('click',function () {
        if ($.trim($('#commentForm #login_name').val()) == "") {
            sweetAlert("操作失败",'用户名不允许为空','error');
        }
        if ($.trim($('#commentForm #email').val()) == "") {
            sweetAlert("操作失败",'邮箱不允许为空','error');
        }
        if ($.trim($('#commentForm #tel').val()) == "") {
            sweetAlert("操作失败",'电话不允许为空','error');
        }
    })
});