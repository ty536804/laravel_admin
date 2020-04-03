@extends('master.base')
@section('title', '管理菜单')
@section("menuname","留言管理")
@section("smallname","留言列表")

@section('css')
    <link href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
    <link href="{{asset('/admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet">
    <style>
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
        .pl0 {
            padding-left: 0;
        }
        .pr0 {
            padding-right: 0;
        }
    </style>
@endsection

@section('js')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/extensions/Buttons/js/buttons.html5.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/jszip/dist/jszip.min.js')}}" type="text/javascript"></script>

    <script src="{{asset('plugins/fileinput/js/fileinput.js')}}"></script>
    <script src="{{asset('/admin/js/plugins/sweetalert/sweetalert.min.js')}}"></script>
    <script type="text/javascript">
        var myTable;
        var uploadfile =  '{{asset('storage/uploadfile/').'/'}}';
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
                    "url":"{{URL::action('Backend\MessageController@messageList')}}",
                    "type":"POST",
                    "dataType":"json",
                    "data":{'_token':'{{ csrf_token()}}'}
                },
                "columns": [
                    { "data": "id"},
                    { "data": "mname"},
                    { "data": "area"},
                    { "data": "tel"},
                    { "data": "com"},
                    { "data": "client"},
                    { "data": "ip"},
                    { "data": "channel"},
                    { "data": "hot"}
                ],
                "columnDefs": [
                    {
                        "render" : function(data, type, row,meta){
                            return '<button  class="btn btn-default btn-sm" onclick="edit('+meta.row+')">查看</button  >';
                        },
                        "targets" :8,
                    },
                ]
            });
            return table;
        }

        /**
         * 查看内容
         */
        function edit() {
            $('.modal-title').empty().html('查看');
            let index  = Number(id),
                data = myTable.rows(index).data()[0];
            $("#create #content").val(data.content);
            $("#create").modal("show");
        }
    </script>
@endsection


@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
{{--                    <div class="col-xs-12" style="margin: 10px 0px;">--}}
{{--                        <div class="col-md-1 ">--}}
{{--                            <div class="btn-group btn-group-sm">--}}
{{--                                <a  class="btn btn-success" href="/backend/detail">添加</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="box-body">
                        <table id="mytable" class="table table-bordered table-striped" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>姓名</th>
                                <th>地区</th>
                                <th>电话</th>
                                <th>来源</th>
                                <th>客户端类型</th>
                                <th>IP</th>
                                <th>留言板块</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div>
    </section>
{{--    modal--}}
    <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body row">
                    <form action="" id="order_data">
                        <div class="form-group">
                            <label class="col-sm-2 " for="content">留言内容</label>
                            <div class="col-sm-10">
                                <textarea id="content" name="content" class="form-control" rows="16" style="min-width: 90%"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <button class="btn btn-default" data-dismiss="modal" id="cancel" type="button">取消</button>
                        {{--<button class="btn btn-success" id="button_id" type="button">保存</button>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
