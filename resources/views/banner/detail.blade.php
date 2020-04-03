@extends('master.base')
@section('title', '图片管理')
@section("menuname",'banner管理')
@section("smallname","banner操作")

@section('css')
    <link href="{{asset('admin/css/plugins/iCheck/custom.css')}}" rel="stylesheet">
    <link href="{{asset('/admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/fileinput/css/fileinput.min.css')}}" xmlns="http://www.w3.org/1999/html">
    <link href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
    @endsection

    @section('js')
            <!-- iCheck -->
    <script src="{{asset('admin/js/plugins/iCheck/icheck.min.js')}}"></script>
    <script src="{{asset('/admin/js/plugins/sweetalert/sweetalert.min.js')}}"></script>
    <script src="{{asset('plugins/fileinput/js/fileinput.js')}}"></script>
    <script src="{{asset('plugins/fileinput/js/fileinput_locale_zh.js')}}"></script>
    <script src="{{asset('assets/js/fileupload.js')}}"></script>
    <script src="{{asset('plugins/laydate/laydate.js')}}"></script> {{--日期控件--}}
    <script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
            let uid = '{{$admin_id}}';
            let file = new upload({ uid:uid,pictype:1001,uploadid:"fileupload",tag:"img_info",token:'<?php echo e(csrf_token());?>'});
            file.initUpload('{!! $info->img_info !!}','{{asset('storage/uploadfile/').'/'}}') ;
        });

        $(".selectpicker").selectpicker({
            width : 300,
            actionsBox:true, //在下拉选项添加选中所有和取消选中的按钮
            countSelectedText:"已选中{0}项",
            selectedTextFormat:"count > 5"
        });

        /**
         * 提交内容
         */
        $(document).on('click','#button_id',function () {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{URL::action('Backend\BannerController@bannerSave')}}",
                data: $("#addform").serialize(),
                success: function (result) {
                    if (result.code == "10000") {
                        swal({title:result.msg,type: 'success'},
                                function () {
                                    window.location.href="/backend/banner/show";
                                });
                    } else {
                        sweetAlert("操作失败",result.msg,'error');
                    }

                },
                error: function (result) {
                    swal({title:"网络错误",type: 'error'});
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
                                    <label class="col-sm-3 control-label">* 名称：</label>
                                    <div class="col-sm-8">

                                        <input id="bname" name="bname"  type="text" class="form-control" value="{{$info->bname}}">
                                    </div>
                                </div>
                                <div class="form-group position">
                                    <label class="col-sm-3 control-label">*显示位置：</label>
                                    <div class="col-sm-8">
                                        <select class="col-sm-12 form-control" id="bposition" name="bposition">
                                            <option value="">请选择</option>
                                            @foreach($position as $val)
                                                <option value="{{$val->id}}"  @if($info->bposition==$val->id) selected="selected" @endif>{{$val->position_name}}</option>
                                            @endforeach
                                        </select>
                                        <span style="color: red" id="tips">如果列表为空请到banner位置列表添加</span>
                                    </div>
                                </div>

                                <div class="form-group banner_skip_url_hide">
                                    <label class="col-sm-3 control-label">*链接：</label>
                                    <div class="col-sm-8">
                                        <input id="target_link" name="target_link"  type="text" class="form-control" value="{{$info->target_link}}">
                                        <span style="color: red;">若无请填#</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">*上传banner图片：</label>
                                    <div class="col-md-8">
                                        <div id="thumb_img_info">
                                            <input type="hidden" id="img_info" name="img_info" value="{{$info->img_info}}"/>
                                            <input id="fileupload" name="fileupload" type="file" multiple>
                                            <input type="hidden" name="_token"  value="{{csrf_token()}}"/>
                                            <span class="help-block text_infor">支持文件格式:xls, xlsx, doc, docx, pdf, jpg, png, jpeg。</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">* 状态</label>
                                    <div class="col-sm-8">
                                        <select class="col-sm-12 form-control" id="astatus" name="astatus">
                                            <option value="1" @if($info->is_show==1) selected @endif>显示</option>
                                            <option value="2" @if($info->is_show==2) selected @endif>隐藏</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-3">
                                        <button class="btn btn-primary btn-block" type="button" id="button_id" name="button_id">提交</button>
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