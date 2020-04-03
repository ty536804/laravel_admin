@extends('master.base')
@section('title', '管理菜单')
@section("menuname","banner管理")
@section("smallname","banner展示位置")

@section('css')
    <link href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
    <style>
        .mt10 {
            margin-top: 10px !important;
        }
        .mb10 {
            margin-bottom: 10px !important;
        }
        .table>thead>tr>th {
            font-weight: normal;
            color: #171616;
            border-bottom-width: 2px;
            ertical-align: bottom;
        }

        table td{
            word-break: break-all;
            word-wrap: break-word;
        }
        .bootstrap-select>.dropdown-toggle.bs-placeholder{
            background: #fff;
        }
    </style>

@endsection

@section('js')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/extensions/Buttons/js/buttons.html5.min.js')}}" type="text/javascript"></script>
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
                "serverSide": false,
                'stateSave':true,
                "retrieve": true,
                "processing": true,
                "autoWidth": false,
                "order": [[ 0, "desc" ]],
                "ajax": {
                    "url":"{{URL::action('Backend\BannerController@positionData')}}",
                    "type":"POST",
                    "dataType":"json",
                    "data":{'_token':'{{ csrf_token()}}'}
                },
                "columns": [
                    { "data": "id"},
                    { "data": "position_name"},
                    // { "data": "image_size"},
                    { "data": "info"},
                    { "data": "is_show"},
                    { "data": "is_show"}
                ],
                "columnDefs": [
                    {
                        "render" : function(data, type, row){
                            let str="";
                            if(row.is_show==1){
                                str+='显示';
                            }else{
                                str+='隐藏';
                            }
                            return str;
                        },
                        "targets" :3,
                    },
                    {
                        "render" : function(data, type, row,meta){
                            if (Number(data)==1) {
                                return '<label class="btn btn-danger btn-sm" onclick="closed('+meta.row+',2)">关闭</label>&nbsp;<button class="btn btn-default btn-sm" onclick="edit('+meta.row+')">编辑</button >';
                            } else {
                                return '<label class="btn btn-info btn-sm" onclick="closed('+meta.row+',1)">开启</label>&nbsp;<button  class="btn btn-default btn-sm" onclick="edit('+meta.row+')">编辑</button  >';
                            }
                        },
                        "targets" :4,
                    }
                ]
            });
            return table;
        }

        /**
         * 开启/关闭
         * */
        function closed(id,_show) {
            let index  = Number(id),
                data = myTable.rows(index).data()[0];
            $("#order_data #id").val(data.id);
            $("#order_data #position_name").val(data.position_name);
            $("#order_data #image_size").val(data.image_size);
            $("#order_data #is_show").val(_show);
            $("#order_data #info").val(data.info);
            subCon();
        }

        /**
         * 编辑
         * */
        function edit(id){
            $('.modal-title').empty().html('编辑');
            let index  = Number(id),
                data = myTable.rows(index).data()[0];
            $("#order_data #id").val(data.id);
            $("#order_data #position_name").val(data.position_name);
            $("#order_data #image_size").val(data.image_size);
            $("#order_data #is_show").val(data.is_show);
            $("#order_data #info").val(data.info);
            $("#order_data #base_url").val(data.base_url);
            $("#create").modal("show");
        }

        /**
         * 提交内容
         * */
        $(document).on('click','#button_id',function () {
            subCon();
        });

        /**
         * 统一提交AJAX
         * */
        function subCon() {
            if ($.trim($("#position_name").val()) == "") {
                sweetAlert("操作失败","名称不能为空",'error');
                return false;
            }
            if ($.trim($("#base_url").val()) == "") {
                sweetAlert("操作失败","跳转地址不能为空",'error');
                return false;
            }
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{URL::action('Backend\BannerController@positionSave')}}",
                data: $("#order_data").serialize(),
                success: function (result) {
                    if (result.code == "10000") {
                        sweetAlert("操作成功",result.msg,'success');
                        myTable.ajax.reload(null,false);
                        $("#create").modal('hide');
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
        }

        /**
         * 创建
         */
        $("#add").on("click", function () {
            $('.modal-title').empty().html('创建');
            $("#order_data #id").val("");
            $("#order_data #position_name").val("");
            $("#order_data #info").val("");
            $("#order_data #image_size").val("");
            $("#order_data #is_show").val(1);
            $("#order_data #base_url").val("#");
            $("#create").modal("show");
        })
    </script>
@endsection


@section('content')
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="col-xs-12 mt10 mb10">
                    <div>
                        <button class="btn btn-primary" id="add">新建</button >
                    </div>
                </div>
                <div class="box-body">
                    <table id="mytable" class="table table-bordered table-striped" cellspacing="0">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>名称</th>
{{--                                <th>图片大小</th>--}}
                            <th>备注</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
{{--            <div class="modal fade in" id="create" aria-hidden="false" style="display: none;">--}}
<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">修改密码</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal m-t" id="order_data">
                    {{csrf_field()}}
                    <input id="id" type="hidden" class="form-control" name="id" value="">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">名称：</label>
                        <div class="col-sm-8">
                            <input id="position_name" name="position_name" minlength="2" type="text" class="form-control"  value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">链接地址：</label>
                        <div class="col-sm-8">
                            <input id="base_url" type="text" class="form-control" name="base_url" value="">
                        </div>
                    </div>
{{--                                <div class="form-group">--}}

                        {{--                                    <label class="col-sm-2" for="content">*图片大小</label>--}}

                        {{--                                    <div class="col-sm-10"  style="margin: 10px 0;">--}}
                        {{--                                        <input class="form-control" type="text" id="image_size" name="image_size" value="">--}}
                        {{--                                    <span style="color: red;">格式：宽*高</span>--}}
                        {{--                                    </div>--}}
                        {{--                                </div>--}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">备注：</label>
                            <div class="col-sm-8">
                                <input id="info" type="text" class="form-control" name="info" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">状态:</label>
                            <div class="col-sm-8">
                                <select class="form-control"  id="is_show" name="is_show" >
                                    <option value="1">显示</option>
                                    <option value="2">隐藏</option>
                                </select>
                            </div>
                        </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="btn-group">
                    <button class="btn btn-default" data-dismiss="modal" id="cancel" type="button">取消
                    </button>
                    <button class="btn btn-success" id="button_id" type="button">保存</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
