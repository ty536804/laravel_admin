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

        .control-label{
            padding: 0;
            line-height: 35px;
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
                    { "data": "image_size"},
                    { "data": "info"},
                    { "data": "is_show"},
                    { "data": "is_show"}
                ],
                "columnDefs": [
                    {
                        "render" : function(data, type, row){
                            var str="";
                            if(row.is_show==1){
                                str+='显示';
                            }else{
                                str+='隐藏';
                            }
                            return str;
                        },
                        "targets" :4,
                    },
                    {
                        "render" : function(data, type, row){
                            var str="";
                            str +="<a class=\"btn btn-sm btn-primary\" onclick='edit("+row.id+")'>编辑</a> ";
//                                    "<a class=\"btn btn-sm btn-danger\" onclick='del("+row.id+")'>删除</a>";
                            return str;
                        },
                        "targets" :5,
                    }
                ]
            });
            return table;
        }

        function edit(id){
            $.ajax({
                type:"POST",
                dataType:"json",
                url: "{{URL::action('Backend\BannerController@positionEdit')}}",
                data:{'_token':'{{ csrf_token() }}','id':id},
                success: function (result) {
                    if (result.code == "10000") {
                        console.log(result.data);
                        $("#id").val(result.data.id);
                        $("#position_name").val(result.data.position_name);
                        $("#image_size").val(result.data.image_size);
                        $("#status").val(result.data.is_show);
                        $("#create").show();
                    } else {
                        swal({title:result.msg,type: 'error'});
                    }
                },
                error: function (result) {
                    swal({title:"网络错误",type: 'error'});
                }
            });
        }


        $(document).on('click','#button_id',function () {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{URL::action('Backend\BannerController@positionSave')}}",
                data: $("#order_data").serialize(),
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

        $("#add").click(function(){
            $("#create").show();
            $("#order_data").reset();
        });
        $("#cancel").click(function(){
            $("#create").hide();
        });
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
                                <th>位置名称</th>
                                <th>图片大小</th>
                                <th>备注</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="modal fade in" id="create" aria-hidden="false" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body row">
                            <form action="" id="order_data">
                                <div class="form-group">
                                    {{csrf_field()}}
                                    <label class="col-sm-2 " for="content">*位置名称</label>
                                    <input type="hidden" id="id" name="id" value="">
                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" id="position_name" name="position_name" value="">
                                    </div>

                                </div>
                                <div class="form-group" style="margin: 10px 0;">

                                    <label class="col-sm-2" for="content">*图片大小</label>

                                    <div class="col-sm-10"  style="margin: 10px 0;">
                                        <input class="form-control" type="text" id="image_size" name="image_size" value="">
                                    <span style="color: red;">格式：宽*高</span>
                                    </div>

                                </div>
                                <div class="form-group">

                                    <label class="col-sm-2" for="content">备注</label>

                                    <div class="col-sm-10">
                                        <input class="form-control" type="text" id="info" name="info" value="">
                                        <br>
                                    </div>

                                </div>
                                <div class="form-group">

                                    <label class="col-sm-2" for="content">*状态</label>

                                    <div class="col-sm-10">
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
        </div>
    </section>
@endsection
