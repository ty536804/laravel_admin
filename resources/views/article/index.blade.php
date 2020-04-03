@extends('master.base')
@section('title', '管理菜单')
@section("menuname","文章管理")
@section("smallname","文章列表")

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
                    "url":"{{URL::action('Backend\ArticleController@articleList')}}",
                    "type":"POST",
                    "dataType":"json",
                    "data":{'_token':'{{ csrf_token()}}'}
                },
                "columns": [
                    { "data": "id"},
                    { "data": "title"},
                    { "data": "summary"},
                    { "data": "thumb_img"},
                    { "data": "admin"},
                    { "data": "is_show"},
                    { "data": "hot"}
                ],
                "columnDefs": [
                    {
                        "targets" :1,
                        "width":200,
                    },
                    {
                        "targets" :2,
                        "width":300,
                    },
                    {
                        "render" : function(data, type, row){
                            if (data == null) {
                                var str="";
                            }else{
                                console.log(uploadfile);
                                var str = '<a href="'+uploadfile+row.thumb_img+'" target="_blank" ><img src="'+uploadfile+row.thumb_img+'" width="60px"/></a>';
                            }
                            return str;
                        },
                        "targets" :3,
                    },
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
                        "targets" :5,
                    },
                    {
                        "render" : function(data, type, row){
                            let str="";
                            str +='<a class=\"btn btn-sm btn-primary\" href="/backend/article/detail?id='+row.id+'">编辑</a> ' +
                                    "<a class=\"btn btn-sm btn-danger\" onclick='del("+row.id+")'>删除</a>";
                            return str;
                        },
                        "targets" :6,
                    },
                ]
            });
            return table;
        }

        /**
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
                    type:"POST",
                    dataType:"json",
                    url: "{{URL::action('Backend\ArticleController@articleSave')}}",
                    data:{'_token':'{{ csrf_token() }}','id':id},
                    success: function (result) {
                        if (result.code == "10000") {
                            swal({title:result.msg,type: 'success'},
                                    function () {
                                        window.location.reload();
                                    })
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
                                <a  class="btn btn-success" href="/backend/article/detail">添加</a>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <table id="mytable" class="table table-bordered table-striped" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>标题</th>
                                <th>摘要</th>
                                <th>缩率图</th>
                                <th>编辑者</th>
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
@endsection
