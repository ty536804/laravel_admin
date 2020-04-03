@extends('master.base')
@section('title', '管理菜单')
@section("menuname","管理员管理")
@section("smallname","管理员列表")

@section('css')
    <link href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/plugins/iCheck/custom.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
@endsection
@section('js')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/extensions/Buttons/js/buttons.html5.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/extensions/Buttons/js/buttons.export.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/jszip/dist/jszip.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/admin/js/plugins/sweetalert/sweetalert.min.js')}}"></script>
    <script src="{{asset('admin/js/plugins/iCheck/icheck.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script type="text/javascript">


        $(window).on('load', function () {

            $('.selectpicker').selectpicker({
                'selectedText': 'cat'
            });

            // $('.selectpicker').selectpicker('hide');
        });



        var myTable
        $(function () {
            myTable =  initDataTable();

        });
        function initDataTable(){
            var table ;
            table  =  $("#mytable").DataTable({
                "oLanguage":{"sUrl":"{{asset('plugins/datatables/jquery.dataTable.cn.txt')}}"},
                "responsive":true,
                "serverSide": false,
                'stateSave':true,
                "retrieve": true,
                "processing": true,
                "autoWidth": false,
                "order": [[ 0, "desc" ]],
                "ajax": {
                    "url":"{{URL::action('Admin\UserController@getListData')}}",
                    "type":"POST",
                    "dataType":"json",
                    "data":{'_token':'{{ csrf_token() }}'}
                },
                "columns": [
                    { "data": "id" },
                    { "data": "nick_name" },
                    { "data": "email" },
                    { "data": "tel" },
                    { "data": "dp_name" },
                    { "data": "position_name" },
                    { "data": "city_id" },
                    { "data": "status" }
                ],
                "columnDefs": [
                    {
                        "render" : function(data, type, row){
                            let objs =$('#city_names').val(),
                                obj = JSON.parse(objs);
                            var str = disivion(data,obj);
                            return str;
                        },
                        "targets" :6,
                    },
                    {
                        "render" : function(data, type, row){
                            if(data==1){
                                var str = "正常";
                            }else{
                                var str = "禁用";
                            }
                            return str;
                        },
                        "targets" :7,
                    },
                    {
                        "render" : function(data, type, row,meta){
                            if (Number(row.status)==1) {
                                return "<a class=\"btn btn-sm btn-danger\" onclick='del("+row.id+",-1)'>禁用</a> <a class=\"btn btn-sm btn-primary\"  onclick='edit("+meta.row+")'>修改</a>";
                            } else {
                                return "<a class=\"btn btn-sm btn-info\" onclick='del("+row.id+",1)'>开启</a> <a class=\"btn btn-sm btn-primary\" onclick='edit("+meta.row+")'>修改</a>";
                            }
                        },
                        "targets" :8,
                    }
                ],
            });
            return table;
        }

        /**
         * 禁用开启账号
         * @param id
         * @param _status
         * @returns {boolean}
         */
        function del(id,_status) {
            swal({
                    title: "确定"+(_status==1 ? '开启':"禁止")+"吗？",
                    // text: "你将无法恢复该虚拟文件！",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定！",
                    closeOnConfirm: false
                },
                function(){
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "{{URL::action('Admin\UserController@delete')}}",
                        data: {'_token':'{{ csrf_token() }}','id':id,"status":_status},
                        success: function (result) {
                            if (result.code == "10000") {
                                myTable.ajax.reload(null,false);
                                sweetAlert("操作成功",result.msg,'success');
                            } else {
                                sweetAlert("操作失败",result.msg,'error');
                            }
                        },
                        error: function (result) {
                            $.each(result.responseJSON.errors, function (k, val) {
                                sweetAlert("操作失败",val[0],'error');
                                return false;
                            });
                        }
                    });
                });
            return false;
        }

        function edit() {
            $('.modal-title').empty().html('编辑');
            let index  = Number(id),
                data = myTable.rows(index).data()[0];
            $("#addform #id").val(data.id);
            $("#addform #nick_name").val(data.nick_name);
            $("#addform #login_name").val(data.login_name);
            $("#addform #email").val(data.email);
            $("#addform #city_id").val("10000");

            $("#addform #tel").val(data.tel);
            $("#addform #pwd").val("");

            $("#addform #department_id").val(data.department_id);
            $("#addform #position_id").val(data.position_id);
            $("#myModal2").modal("show");
        }

        //提交账号
        $('#addpower').on('click', function () {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{URL::action('Admin\UserController@save')}}",
                data: $("#addform").serialize(),
                success: function (result) {
                    if (result.code == "10000") {
                        sweetAlert("操作成功",result.msg,'success');
                        myTable.ajax.reload(null,false);
                        $("#myModal2").modal('hide');
                    } else {
                        sweetAlert("操作失败",result.msg,'error');
                    }
                },
                error: function (result) {
                    $.each(result.responseJSON.errors, function (k, val) {
                        sweetAlert("操作失败",val[0],'error');
                        return false;
                    });
                }
            });
            return false;
        });

        //添加用户
        $("#btn").on("click",function () {
            $("#addform #id").val("");
            $("#addform #nick_name").val("");
            $("#addform #login_name").val("");
            $("#addform #email").val("");
            $("#addform #city_id").val("10000");

            $("#addform #tel").val("");
            $("#addform #pwd").val("");

            $("#addform #department_id").val("");
            $("#addform #position_id").val("");
        });

        $('#department_id').on('change',function () {
            var options=$("#department_id option:selected").val();
            console.log(options);
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{URL::action('Admin\UserController@Linkage')}}",
                data: {'_token':'{{ csrf_token() }}','department_id':options},
                success: function (result) {
                    console.log(result);
                    var str = '';
                    $.each(result,function (ids,obj) {
                        str +="<option value=\" "+obj.id+" \">"+obj.position_name+"</option>";
                    });
                    $('#position_id').empty().append(str);
                },
            });
            return false;
        });
        function disivion(arr,obj) {
            let str =[],
                brr = arr.split(',');
            for (let i=0;i<brr.length;i++){
                for (let k in obj){
                    if (brr[i] == k){
                        str.push(obj[k]);
                    }
                }
            }
            str.join();
            return str
        }
    </script>

@endsection


@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"></h3>
                        <button type="button" id="btn" class="btn btn-primary" data-toggle="modal" data-target="#myModal2">
                            添加用户
                        </button>
                        <input type="hidden" id="city_names" value="{{$city_names}}">
                    </div>  <!-- /.box-header -->
                    <div class="box-body">
                        <table id="mytable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>用户ID</th>
                                <th>昵称</th>
                                <th>邮箱</th>
                                <th>手机号</th>
                                <th>部门</th>
                                <th>职位</th>
                                <th>城市</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div>
    </section>

    <div class="modal inmodal" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">用户管理</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal m-t" id="addform">
                        {{csrf_field()}}
                        <div class="form-group">
                            <input id="id" name="id" type="hidden" value="{{$info->id}}">
                            <label class="col-sm-3 control-label">用户名称：</label>
                            <div class="col-sm-8">
                                <input id="nick_name" name="nick_name"  type="text" class="form-control" value="{{$info->nick_name}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">用户登陆账号：</label>
                            <div class="col-sm-8">
                                <input id="login_name" name="login_name" type="text" class="form-control" value="{{$info->login_name}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">邮箱：</label>
                            <div class="col-sm-8">
                                <input id="email" name="email" type="text" class="form-control" value="{{$info->email}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">密码：</label>
                            <div class="col-sm-8">
                                <input id="pwd" name="pwd" type="text" class="form-control" value="{{$info->pwd}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">电话：</label>
                            <div class="col-sm-8">
                                <input id="tel" name="tel" type="text" class="form-control" value="{{$info->tel}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">部门：</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="department_id" id="department_id">
                                    <option value="">--请选择--</option>
                                    @foreach($dp_name as $k=>$v)
                                        <option value="{{$v->id}}" @if($info->id == $v->id ) selected="selected" @endif> {{$v->dp_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">职位：</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="position_id" id="position_id">
                                    @foreach($pt_name as $k=>$v)
                                        <option value="{{$v->id}}" @if($info->id == $v->id ) selected="selected" @endif>{{$v->position_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" id="city_id" name="city_id" value="10000">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">状态：</label>
                            <div class="radio i-checks">
                                <label>
                                    <input type="radio" value="1" checked="" name="status" id="status"> <i></i> 正常</label>
                                <label>
                                    <input type="radio" value="0" name="status" id="status"><i></i> 禁用</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" id="addpower" name="addpower">提交</button>
                    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
@endsection