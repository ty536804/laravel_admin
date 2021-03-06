@extends('master.base')
@section('title', '管理菜单')
@section("menuname","部门管理")
@section("smallname","部门操作")

@section('css')
    <link href="{{asset('admin/css/plugins/iCheck/custom.css')}}" rel="stylesheet">
    <link href="{{asset('/admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
    <link href="{{ asset('admin/css/plugins/jsTree/style.min.css')}}" rel="stylesheet"/>
@endsection
@yield('css')
    <style>
        #full {
            height:600px;
            overflow-y:scroll;
        }
    </style>
@section('js')
    <!-- iCheck -->
    {{--<script src="{{asset('admin/js/plugins/iCheck/icheck.min.js')}}"></script>--}}
    <script src="{{asset('admin/js/plugins/iCheck/icheck.min.js')}}"></script>
    <script src="{{asset('admin/js/plugins/jsTree/jsTree.js')}}"></script>
    <script src="{{asset('admin/js/plugins/sweetalert/sweetalert.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });

        });

        $('#addpower').on('click', function () {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{URL::action('Admin\DepartmentController@save')}}",
                data:$("#addform").serialize(),
                success: function (result) {
                    if (result.code == "10000") {
                        swal({title:result.msg,type: 'success'},function () {
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

        $('#full').jstree(
            {
                plugins : ["checkbox"],
                "types" : { "file" : { "icon" : "jstree-file" }
            }
        });
        $('#full').on('click',function(){
            var _html = '|';
            var route_id = [];
            $('#full li').each(function(){
                var _this = $(this);

                var pid =  (_this.parent().parent().data('id') ? _this.parent().parent().data('id') : '');
                if(_this.parent().parent().attr('aria-selected') && _this.parent().parent().attr('aria-selected')=='true'){
                    route_id.push(_this.data('id'));
                }else{
                    if(_this.attr('aria-selected') == 'true'){
                        route_id.push(pid);
                        route_id.push(_this.data('id'));
                    }
                }
            });
            var newArr = fillter_num(route_id);
            for(var i=0;i<newArr.length;i++){
                _html += newArr[i] + '|' ;
            }
            $('#powerid').val(_html);
        });

        function fillter_num(arr) {
            var new_arr=[];
            for(var i=0;i<arr.length;i++) {
                var items=arr[i];
                //判断元素是否存在于new_arr中，如果不存在则插入到new_arr的最后
                if($.inArray(items,new_arr)==-1) {
                    new_arr.push(items);
                }
            }
            return new_arr;
        }
    </script>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-xs-6">
            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title"></h3>
                </div>
                <div class="box-body">
                    <form class="form-horizontal m-t" id="addform">
                        <div class="col-md-6">
                            {{csrf_field()}}
                            <div class="form-group">
                                <input id="id" name="id" type="hidden" value="{{$info->id}}">
                                <input id="level" name="level" type="hidden" value="{{$level}}">
                                <input id="parent_id" name="parent_id" type="hidden" value="{{$parent_id}}">
                                <label class="col-sm-4 control-label">部门名称：</label>
                                <div class="col-sm-8">
                                    <input id="dp_name" name="dp_name"  type="text" class="form-control" value="{{$info->dp_name}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">状态：</label>
                                <div class="radio i-checks">
                                    <label>
                                        <input type="radio" value="1"
                                        @if($info->status == 1)
                                            checked=""
                                        @endif
                                        name="status" id="status"> <i></i> 正常</label>
                                    <label>
                                        <input type="radio" value="0"
                                        @if($info->status == 0)
                                            checked=""
                                        @endif
                                        name="status" id="status"><i></i> 禁用</label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="powerid" name="powerid" class="form-control"
                               @if(!empty($powerid))
                               value="|{{implode('|',$powerid)}}|"
                               @else
                               value="|"
                                @endif
                        />
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-3">
                            <button class="btn btn-primary" type="button" id="addpower" name="addpower">提交</button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.box-body -->
        </div>
        <div class="col-xs-6">
            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title"></h3>
                </div>
                <div class="box-body">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">部门权限：</label>
                            <div class="col-sm-8 tree" id="full">
                                @foreach($department_rule as $department)
                                    <ul>
                                        <li class="jstree-open" data-id = {{ $department['id'] }}

                                        >
                                        {{ $department['pname'] }}
                                        @if(!empty($department['child']))
                                            @foreach($department['child'] as $v)
                                                <ul>
                                                    <li data-id = {{ $v['id'] }}
                                                    @if($powerid && in_array($v['id'],$powerid))
                                                            data-jstree='{ "selected" : true }'
                                                    @endif
                                                    >
                                                    {{ $v['pname'] }}
                                                    </li>
                                                </ul>
                                                @endforeach
                                                @endif
                                                </li>
                                    </ul>
                                @endforeach

                            </div>
                        </div>
                    </div>



            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.box -->

    </div><!-- /.row -->
</section><!-- /.content -->
@endsection


