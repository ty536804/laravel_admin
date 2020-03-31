@section('MasterJs')

<!-- jQuery 3 -->
<script src="{{asset('bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- Morris.js charts -->
<script src="{{asset('bower_components/raphael/raphael.min.js')}}"></script>
{{--<script src="{{asset('bower_components/morris.js/morris.js')}}"></script>--}}
<!-- Sparkline -->
<script src="{{asset('bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')}}"></script>
<!-- jvectormap -->
<script src="{{asset('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{asset('plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('bower_components/jquery-knob/dist/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('bower_components/moment/min/moment.min.js')}}"></script>
<script src="{{asset('bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<!-- datepicker -->
<script src="{{asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
<!-- Slimscroll -->
<script src="{{asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- iCheck 1.0.1 -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('bower_components/fastclick/fastclick.js')}}"></script>
<script src="{{asset('/admin/js/plugins/sweetalert/sweetalert.min.js')}}"></script>

<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
{{--<script src="{{asset('dist/js/pages/dashboard.js')}}"></script>--}}
<!-- AdminLTE for demo purposes -->


<script src="{{asset('dist/js/demo.js')}}"></script>
<script src="{{asset('admin/js/plugins/iCheck/icheck.min.js')}}"></script>
<script type="application/javascript">
   $(function () {
       //修改密码
       $('.head_sub').on('click',function () {
           if ($.trim($('#login_name').val())=="") {
               sweetAlert("操作失败","用户名不能为空","error");
           }
           if ($.trim($('#pwd').val())=="") {
               sweetAlert("操作失败","旧密码不能为空","error");
           }
           if ($.trim($('#newpwd').val())=="") {
               sweetAlert("操作失败","新密码不能为空","error");
           }
           subCon("{{\Illuminate\Support\Facades\URL::action("Admin\UserController@change")}}","update_pwd","changePwd")
       });

       //修改信息
       $('.update_info').on('click',function () {
           if ($.trim($('#login_name').val())=="") {
               sweetAlert("操作失败","用户名不能为空","error");
           }

           if ($.trim($('#email').val())=="") {
               sweetAlert("操作失败","邮箱不能为空","error");
           }

           if ($.trim($('#tel').val())=="") {
               sweetAlert("操作失败","电话不能为空","error");
           }

           subCon("{{\Illuminate\Support\Facades\URL::action("Admin\UserController@updateInfo")}}","commentForm","myModal")
       });

       //提交内容
       function subCon(_URL,_data,_modal) {
           $.ajax({
               type: "POST",
               dataType: "json",
               url: _URL,
               data: $("#"+_data+"").serialize(),
               success: function (result) {
                   if (result.code == "10000") {
                       if (_data == "update_pwd") {
                           window.location.href = "{{URL::action('Admin\AdminController@logout')}}";
                       }
                       $("#"+_modal+"").modal("hide");
                       sweetAlert("操作成功",result.msg,'success');
                   } else {
                       sweetAlert("操作失败",result.msg,'error');
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
       }
   })
</script>
@show