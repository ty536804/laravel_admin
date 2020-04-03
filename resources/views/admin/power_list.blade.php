@extends('master.base')
@section('title', '管理菜单')
@section("menuname","权限管理")
@section("smallname","权限列表")

@section('css')
    <link href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">

@endsection

@section('js')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/extensions/Buttons/js/buttons.html5.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/extensions/Buttons/js/buttons.export.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/jszip/dist/jszip.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/admin/js/plugins/sweetalert/sweetalert.min.js')}}"></script>


    <script type="text/javascript">
        var myTable;
        $(function () {
            myTable =  initDataTable();
        });
        function initDataTable(){
            var table ;
            table  =  $("#mytable").DataTable({
                "oLanguage":{"sUrl":"{{asset('plugins/datatables/jquery.dataTable.cn.txt')}}"},
                "responsive":true,
                "serverSide": true,
                'stateSave':true,
                "retrieve": true,
                "processing": true,
                "autoWidth": false,
                "order": [[ 0, "desc" ]],
                "ajax": {
                    "url":"{{URL::action('Admin\PowerController@getListData')}}",
                    "type":"POST",
                    "dataType":"json",
                    "data":{'_token':'{{ csrf_token() }}'}
                },
                "columns": [
                    { "data": "id" },
                    { "data": "pname" },
                    { "data": "icon" },
                    { "data": "purl" },
                    { "data": "pindex" },
                    { "data": "status" }
                ],
                "columnDefs": [
                    {
                        "render" : function(data, type, row){
                            if(data==1){
                                var str = "正常";
                            }else{
                                var str = "禁用";
                            }
                            return str;
                        },
                        "targets" :5,
                    },
                    {
                        "render" : function(data, type, row ,meta){
                        // <button class=\"btn btn-sm btn-danger\" onclick='del("+row.id+")'>禁用</button> " +
                            var str="<a class=\"btn btn-sm btn-primary\" href=\"/admin/power/updateview?id=" + row.id+ "\">编辑</a>";
                            if(row.parent_id == 0){
                                str +=" <a class=\"btn btn-sm btn-success\" href=\"/admin/power/view?pid="+row.id+"\" >添加二级</a>";
                            }
                            return str;
                        },
                        "targets" :6,
                    }
                ],
            });
            return table;
        }
        function del(id) {
            swal({
                    title: "确定删除吗？",
                    text: "你将无法恢复该虚拟文件！",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定删除！",
                    closeOnConfirm: false
                },
                function(){
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "{{URL::action('Admin\PowerController@delete')}}",
                        data: {'_token':'{{ csrf_token() }}','id':id},
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

        /**
         * 提交内容
         */
        $('#addpower').on('click', function () {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{URL::action('Admin\PowerController@save')}}",
                data: $("#addform").serialize(),
                success: function (result) {
                    if (result.code == "10000") {
                        swal({title:result.msg,type: 'success'},
                            function () {
                                window.location.reload();
                            });
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
    </script>
@endsection


@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"></h3>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2">
                                添加一级权限
                            </button>
                    </div>  <!-- /.box-header -->
                    <div class="box-body">
                        <table id="mytable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>权限ID</th>
                                <th>权限名称</th>
                                <th>ICON</th>
                                <th>URL</th>
                                <th>排序</th>
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
                            {{--<input id="id" name="id" type="hidden" value="{{$info->id}}">--}}
                            <input id="purl" name="purl" type="hidden" class="form-control" value="#">
                            <label class="col-sm-3 control-label">权限名称：</label>
                            <div class="col-sm-8">
                                <input id="pname" name="pname"  type="text" class="form-control" value="{{$info->pname}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">权限图标：</label>
                            <div class="col-sm-8">
                                <input id="icon" name="icon"  type="text" class="form-control" value="{{$info->icon}}">
                            </div>
                        </div>
                        {{--<div class="form-group">--}}
                            {{--<label class="col-sm-3 control-label">链接地址：</label>--}}
                            {{--<div class="col-sm-8">--}}
                                <input id="purl" name="purl" type="hidden" class="form-control" value="#">
                            {{--</div>--}}
                        {{--</div>--}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">排序：</label>
                            <div class="col-sm-8">
                                <input id="pindex" name="pindex"  type="text" class="form-control" value="{{$info->pindex}}">
                            </div>
                        </div>
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
