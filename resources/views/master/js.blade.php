<!-- 全局js -->
<script src="{{asset("/admin/js/jquery.min.js?v=2.1.4")}}"></script>
<script src="{{asset("/admin/js/bootstrap.min.js?v=3.3.6")}}"></script>
<script src="{{asset("/admin/js/plugins/metisMenu/jquery.metisMenu.js")}}"></script>
<script src="{{asset("/admin/js/plugins/slimscroll/jquery.slimscroll.min.js")}}"></script>
<script src="{{asset("/admin/js/plugins/layer/layer.min.js")}}"></script>
<script src="{{asset('/admin/js/plugins/sweetalert/sweetalert.min.js')}}"></script>

<!-- 自定义js -->
<script src="{{asset("/admin/js/hAdmin.js?v=4.1.0")}}"></script>
<script type="text/javascript" src="{{asset("/admin/js/index.js")}}"></script>
<script type="application/javascript">
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

        subCon("{{\Illuminate\Support\Facades\URL::action("Admin\UserController@change")}}", $("#update_pwd").serialize(),'changePwd');
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
        subCon("{{\Illuminate\Support\Facades\URL::action("Admin\UserController@updateInfo")}}", $("#commentForm").serialize(),"myModal");
    });

    //提交内容
    function subCon(postUrl,_data,_id) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: postUrl,
            data: _data,
            success: function (result) {
                if (Number(result.code) == 10000) {
                    sweetAlert("操作成功",result.msg,'success');
                    $("#"+_id+"").modal('hide');
                } else {
                    sweetAlert("操作失败",result.msg,'error');
                }
            }
        });
        return false;
    }
</script>
{{--<!-- 第三方插件 -->--}}
{{--<script src="{{"/admin/js/plugins/pace/pace.min.js"}}"></script>--}}