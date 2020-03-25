@extends('master.base')
@section('title', '众包管理')
@section("menuname","小e头像管理")
@section("smallname","小e头像审核列表")

@section('css')
    <link href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/datatables/extensions/Buttons/css/buttons.dataTables.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/css/plugins/iCheck/custom.css')}}" rel="stylesheet">
    <link href="{{asset('/admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
@endsection

@section('js')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/extensions/Buttons/js/dataTables.buttons.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/extensions/Buttons/js/buttons.html5.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/extensions/Buttons/js/buttons.export.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/jszip/dist/jszip.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin/js/plugins/iCheck/icheck.min.js')}}"></script>
    <script src="{{asset('/admin/js/plugins/sweetalert/sweetalert.min.js')}}"></script>
    <script src="{{asset('/admin/js/EdxAdminYPublic.js')}}"></script>

    <script type="text/javascript">
        var myTable;
        var newMyTable;
        var upMyTable;
        var uploadfile =  '{!! uploadfile() !!}';
        $(function () {
            myTable =  initDataTable();
            newMyTable = initInterview();
            upMyTable = initFailure();
        });
        //展示所有未审核的头像
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
                searching : false,
                "order": [[ 0, "desc" ]],
                "ajax": {
                    "url":"{{URL::action('Fan\CouriersController@headPortraitListData')}}",
                    "type":"POST",
                    "dataType":"json",
                    "data":{'_token':'{{ csrf_token() }}','head_portrait_status':1}
                },
                "columns": [
                    { "data": "id" },
                    { "data": "get_city_id.aname" },
                    { "data": "real_name" },
                    { "data": "examine_avatar" },
                    { "data": "head_portrait_status" },
                ],
                "columnDefs": [
                    {
                        'targets' : [1],  //关闭某一行排序
                        'orderable' : false
                    },
                    {
                        "render" : function(data, type, row, meta){
                            var str='<img src="'+uploadfile+data+'" alt=\"\" width="46px">';
                            return str;
                        },
                        "targets" :3,
                    },
                    {
                        "render" : function(data, type, row){
                            if (data == 1){
                                var str="未审核";
                            }else{
                                var str="错误信息";
                            }
                            return str;
                        },
                        "targets" :4,
                    },
                    {
                        "render" : function(data, type, row, meta){
                            var str="<button class=\"btn btn-sm btn-success\" onclick='portrait("+meta.row+")'>查看详情</button> " +
                                "<a class=\"btn btn-sm btn-primary\" onclick='updateStatus("+row.id+",2)'>通过</a> " +
                                "<a class=\"btn btn-sm btn-danger\" onclick='refuseStatus("+row.id+",3)'>拒绝</a> ";
                            return str;
                        },
                        "targets" :5,
                    }
                ],
            });
            return table;
        }
        //发出面试邀请
        function initInterview(){
            var table ;
            table  =  $("#interview").DataTable({
                "oLanguage":{"sUrl":"{{asset('plugins/datatables/jquery.dataTable.cn.txt')}}"},
                "responsive":true,
                "serverSide": true,
                'stateSave':true,
                "retrieve": true,
                "processing": true,
                "autoWidth": false,
                "order": [[ 0, "desc" ]],
            "ajax": {
                    "url":"{{URL::action('Fan\CouriersController@headPortraitListData')}}",
                    "type":"POST",
                    "dataType":"json",
                    "data":{'_token':'{{ csrf_token() }}','head_portrait_status':2}
                },
                "columns": [
                    { "data": "id" },
                    { "data": "get_city_id.aname" },
                    { "data": "real_name" },
                    { "data": "examine_avatar" },
                    { "data": "head_portrait_status" },
                ],
                "columnDefs": [
                    {
                        "render" : function(data, type, row, meta){
                            var str='<img src="'+uploadfile+data+'" alt=\"\" width="46px">';
                            return str;
                        },
                        "targets" :3,
                    },
                    {
                        "render" : function(data, type, row){
                            if (data == 2){
                                var str="审核通过";
                            }else{
                                var str="错误信息";
                            }
                            return str;
                        },
                        "targets" :4,
                    },
                ],
            });
            return table;
        }
        //未通过申请
        function initFailure(){
            var table ;
            table  =  $("#freight_tab").DataTable({
                "oLanguage":{"sUrl":"{{asset('plugins/datatables/jquery.dataTable.cn.txt')}}"},
                "responsive":true,
                "serverSide": true,
                'stateSave':true,
                "retrieve": true,
                "processing": true,
                "autoWidth": false,
                "order": [[ 0, "desc" ]],
                "ajax": {
                    "url":"{{URL::action('Fan\CouriersController@headPortraitListData')}}",
                    "type":"POST",
                    "dataType":"json",
                    "data":{'_token':'{{ csrf_token() }}','head_portrait_status':3}
                },
                "columns": [
                    { "data": "id" },
                    { "data": "get_city_id.aname" },
                    { "data": "real_name" },
                    { "data": "examine_avatar" },
                    { "data": "head_portrait_status" },
                ],
                "columnDefs": [
                    {
                        "render" : function(data, type, row, meta){
                            var str='<img src="'+uploadfile+data+'" alt=\"\" width="46px">';
                            return str;
                        },
                        "targets" :3,
                    },
                    {
                        "render" : function(data, type, row){
                            if (data == 3){
                                var str="审核未通过";
                            }else{
                                var str="错误信息";
                            }
                            return str;
                        },
                        "targets" :4,
                    },
                ],
            });
            return table;
        }
        function portrait(i) {
            $('#myModal2').modal('show');
            var index  = Number(i);
            var data = myTable.rows(index).data()[0];
            $('#real_name').text(data.real_name);
            $('#tel').text(data.tel);
            $('#avatar').attr("src",uploadfile+data.examine_avatar);
            $('#id_card').attr("src",uploadfile+data.id_card);
            $('#id_card_back').attr("src",uploadfile+data.id_card_back);
            $('#id_card_end').attr("src",uploadfile+data.id_card_end);
        }
        function updateStatus(id,head_portrait_status) {
            var url = "{{URL::action('Fan\CouriersController@headPortraitListSave')}}";
            var data = {'_token':'{{ csrf_token() }}','id':id,'head_portrait_status':head_portrait_status};
            isConfirm(url,data);
        }
        function refuseStatus(id,head_portrait_status) {
            var url = "{{URL::action('Fan\CouriersController@headPortraitListSave')}}";
            var data = {'_token':'{{ csrf_token() }}','id':id,'head_portrait_status':head_portrait_status};
            isConfirm(url,data);
        }
    </script>

@endsection


@section('content')
    <section class="content">
        <div class="nav-tabs-custom">
                <ul id="myTab" class="nav nav-tabs btn-box">
                    <li class="active"><a href="#fetch" data-toggle="tab">未审核</a></li>
                    <li><a href="#freight" data-toggle="tab">审核通过</a></li>
                    <li><a href="#promotion" data-toggle="tab">审核未通过</a></li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade in active" id="fetch">
                        @include('compress.head_protrait.weishenhe')
                    </div>
                    <div class="tab-pane fade" id="freight">
                        @include('compress.head_protrait.shenhetongguo')
                    </div>
                    <div class="tab-pane fade" id="promotion">
                        @include('compress.head_protrait.shenheweitongguo')
                    </div>
            </div>
        </div>



    </section>
    <div class="modal inmodal" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">小e信息详情</h4>
                </div>
                <div class="modal-body">
                    <div class="row invoice-info">
                        <div class="col-sm-6 invoice-col">
                            <b>人员名称 : </b> <span id="real_name"></span><br>
                            <br>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6 invoice-col">
                            <b>电话 : </b> <span id="tel"></span><br>
                            <br>
                        </div>
                        <!-- /.col -->

                        <div class="col-sm-3 invoice-col">
                            <img src="" alt="头像" title="头像" id="avatar" width="90%">
                        </div>
                        <div class="col-sm-3 invoice-col">
                            <img src="" alt="手持照" title="手持照" id="id_card" width="90%">
                        </div>
                        <div class="col-sm-3 invoice-col">
                            <img src="" alt="身份证反面" title="身份证反面" id="id_card_back" width="90%">
                        </div>
                        <div class="col-sm-3 invoice-col">
                            <img src="" alt="身份证正面" title="身份证正面" id="id_card_end" width="90%">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>


@endsection