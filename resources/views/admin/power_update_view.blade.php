@extends('master.base')
@section('title', '管理菜单')
@section("menuname","权限管理")
@section("smallname","权限操作")

@section('css')
    <link href="{{asset('admin/css/plugins/iCheck/custom.css')}}" rel="stylesheet">
    <link href="{{asset('/admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">

@endsection

@section('js')
    <!-- iCheck -->
    <script src="{{asset('admin/js/plugins/iCheck/icheck.min.js')}}"></script>
    <script src="{{asset('/admin/js/plugins/sweetalert/sweetalert.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });

        });

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
                        swal({title:result.msg,type: 'success'},function () {
                            window.location.href = "{{URL::action('Admin\PowerController@list')}}";
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
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title"></h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-8">
                            <form class="form-horizontal m-t" id="addform">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <input id="id" name="id" type="hidden" value="{{$info->id}}">
                                    <label class="col-sm-3 control-label">权限名称：</label>
                                    <div class="col-sm-8">
                                        <input id="pname" name="pname"  type="text" class="form-control" value="{{$info->pname}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">权限图标：</label>
                                    <div class="col-sm-8">
                                        <input id="icon" name="icon"  type="text" placeholder="fa-user-o" class="form-control" value="{{$info->icon}}">
                                    </div>
                                </div>
                                @if($pid != 0)
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">归属权限：</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="parent_id" id="parent_id">
                                                @foreach($pname as $k=>$v)
                                                    <option value="{{$v->id}}"  @if($info->parent_id == $v->id ) selected="selected" @endif>{{$v->pname}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">权限连接：</label>
                                    <div class="col-sm-8">
                                        <input id="purl" name="purl" type="text" placeholder="Admin\UserController@list" class="form-control" value="{{$info->purl}}">
                                    </div>
                                </div>
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
                                            <input type="radio" value="1" checked="" name="status" id="status" @if($info->status == 1)  checked="" @endif> <i></i> 正常</label>
                                        <label>
                                            <input type="radio" value="0" name="status" id="status" @if($info->status == 0)  checked="" @endif><i></i> 禁用</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-6 col-sm-offset-3">
                                        <button class="btn btn-primary btn-block" type="button" id="addpower" name="addpower">提交</button>
                                    </div>
                                </div>

                            </form>
                        </div>

                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!--/.col (right) -->
        </div><!-- /.row -->
    </section><!-- /.content -->
@endsection