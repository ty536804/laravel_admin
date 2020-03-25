@extends('master.base')
@section('title', '管理菜单')
@section("menuname","管理员管理")
@section("smallname","管理员操作")

@section('css')
    <link href="{{asset('admin/css/plugins/iCheck/custom.css')}}" rel="stylesheet">
    <link href="{{asset('/admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />

@endsection

@section('js')
    <!-- iCheck -->
    <script src="{{asset('admin/js/plugins/iCheck/icheck.min.js')}}"></script>
    <script src="{{asset('/admin/js/plugins/sweetalert/sweetalert.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>


    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
        $(document).on('click','#addpower',function () {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{URL::action('Admin\UserController@save')}}",
                data: $("#addform").serialize(),
                success: function (result) {
                    if (result.code == "10000") {
                        window.location.href = "{{URL::action('Admin\UserController@list')}}";
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
        $('#department_id').on('change',function () {
            var options=$("#department_id option:selected").val();
            console.log(options);
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{URL::action('PublicController@Linkage')}}",
                data: {'_token':'{{ csrf_token() }}','department_id':options,'dis':"department"},
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
        $('#addpower').on('click',function () {
            $('#city_id').val($('.selectpicker').val().join(','));
        })
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
                                    <label class="col-sm-3 control-label">用户昵称：</label>
                                    <div class="col-sm-8">
                                        <input id="nick_name" name="nick_name"  type="text" class="form-control" value="{{$info->nick_name}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">邮箱：</label>
                                    <div class="col-sm-8">
                                        <input id="email" name="email" type="email" class="form-control" value="{{$info->email}}">
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
                                                <option value="{{$v->id}}" @if($info->department_id == $v->id ) selected="selected" @endif> {{$v->dp_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">职位：</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="position_id" id="position_id">
                                            <option value="10000">全国</option>
                                            {{--@if(!empty($position))--}}
                                                @foreach($pt_name as $k=>$v)
                                                <option value="{{$v->id}}" @if($info->position_id == $v->id ) selected="selected" @endif>{{$v->position_name}}</option>
                                                @endforeach
                                            {{--@endif--}}
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">城市：</label>
                                        @include('compress.navigation_bar.city_all')
                                </div>
                                <label class="col-sm-3 control-label"></label><font color="#d2691e">城市填写要查询数据的城市(查询所有城市选择全国即可)</font>
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
                                    <div class="col-sm-4 col-sm-offset-3">
                                        <button class="btn btn-primary btn-block" type="button" id="addpower" name="addpower">提交</button>
                                    </div>
                                </div>
                            </form>


                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!--/.col (right) -->
        </div><!-- /.row -->
    </section><!-- /.content -->
@endsection