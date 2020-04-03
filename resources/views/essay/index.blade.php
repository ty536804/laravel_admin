@extends('master.base')
@section('title', '管理菜单')
@section("menuname","文章管理")
@section("smallname","首页列表")

@section('css')
    <link href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
    <link href="{{asset('/admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet">
    <style>
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
                    "url":"{{URL::action('Backend\EssayController@easyList')}}",
                    "type":"POST",
                    "dataType":"json",
                    "data":{'_token':'{{ csrf_token()}}',"id":1}
                },
                "columns": [
                    { "data": "id"},
                    { "data": "essay_title"},
                    { "data": "posi.position_name"},
                    { "data": "id"}
                ],
                "columnDefs": [
                    {
                        "targets" :1,
                        "width":200,
                    },
                    {
                        "render" : function(data, type, row){
                            let str="";
                            str +='<a class=\"btn btn-sm btn-primary\" href="/backend/essay/detail?id='+row.id+'">编辑</a> ' +
                                    "<a class=\"btn btn-sm btn-danger\" onclick='del("+row.id+")'>删除</a>";
                            return str;
                        },
                        "targets" :3,
                    },
                ]
            });
            return table;
        }

        /***
         * 删除
         * @param id
         */
        function del(id) {
            swal({
                title: "确定要删除吗？",
                // text: "删除后可就无法恢复了。",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "是的，我要执行！",
                confirmButtonColor: "#ec6c62",
                cancelButtonText: "容我三思"
            }, function (isConfirm) {
                if (!isConfirm) return;
                $.ajax({
                    type:"GET",
                    dataType:"json",
                    url: "{{URL::action('Backend\EssayController@essayDel')}}",
                    data:{'_token':'{{ csrf_token() }}','id':id},
                    success: function (result) {
                        if (result.code == "10000") {
                            sweetAlert("操作成功",result.msg,'success');
                            myTable.ajax.reload(null,false);
                        } else {
                            swal({title:result.msg,type: 'error'});
                        }
                    },
                    error: function (result) {
                        swal({title:"网络错误",type: 'error'});
                    }
                });
            });
        }
    </script>
@endsection


@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="col-xs-12" style="margin: 10px 0px;">
                        <div class="col-md-1 ">
                            <div class="btn-group btn-group-sm">
                                <a  class="btn btn-success" href="/backend/essay/detail">添加</a>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <table id="mytable" class="table table-bordered table-striped" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>标题</th>
                                <th>模块</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div>
    </section>
@endsection
