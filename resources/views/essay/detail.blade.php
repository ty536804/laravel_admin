@extends('master.base')
@section('title', '图片管理')
@section("menuname",'文章管理')
@section("smallname","文章操作")

@section('css')
    <link href="{{asset('admin/css/plugins/iCheck/custom.css')}}" rel="stylesheet">
    <link href="{{asset('/admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/fileinput/css/fileinput.min.css')}}" xmlns="http://www.w3.org/1999/html">
    <link href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" />
    {{--文本编辑器--}}
    <link rel="stylesheet" type="text/css" href="{{asset("/plugins/markdown/bootstrap-markdown.min.css")}}" />
    @endsection

    @section('js')
            <!-- iCheck -->
    <script src="{{asset('/admin/js/plugins/iCheck/icheck.min.js')}}"></script>
    <script src="{{asset('/admin/js/plugins/sweetalert/sweetalert.min.js')}}"></script>
    <script src="{{asset('/plugins/fileinput/js/fileinput.js')}}"></script>
    <script src="{{asset('/plugins/fileinput/js/fileinput_locale_zh.js')}}"></script>
    <script src="{{asset('/assets/js/fileupload.js')}}"></script>
    <script src="{{asset('/plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>
    {{--文本编辑器--}}
    <script type="text/javascript" src="{{asset("/plugins/markdown/js/markdown.js")}}"></script>
    <script type="text/javascript" src="{{asset("/plugins/markdown/js/to-markdown.js")}}"></script>
    <script type="text/javascript" src="{{asset("/plugins/markdown/js/bootstrap-markdown.js")}}"></script>
    <script type="text/javascript" src="{{asset("/plugins/markdown/js/bootstrap-markdown.zh.js")}}"></script>
    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
            let uid = '{{$admin_id}}';
            let file = new upload({ uid:uid,pictype:1001,uploadid:"fileupload",tag:"essay_img_info",token:'<?php echo e(csrf_token());?>'});
            file.initUpload('{!! $info->essay_img_info !!}','{{asset('storage/uploadfile/').'/'}}') ;
        });
        $(".selectpicker").selectpicker({
            width : 300,
            actionsBox:true, //在下拉选项添加选中所有和取消选中的按钮
            countSelectedText:"已选中{0}项",
            selectedTextFormat:"count > 5"
        });

        /**
         * 提交
         */
        $(document).on('click','#button_id',function () {
            if ($.trim($("#essay_title").val()) == "") {
                sweetAlert("操作失败","标题不能为空",'error');
            }

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{URL::action('Backend\EssayController@essayAdd')}}",
                data: $("#addform").serialize(),
                success: function (result) {
                    if (result.code == "10000") {
                        swal({title:result.msg,type: 'success'},
                        function () {
                            let pid = $("#addform #banner_position_id").val();
                            let _url = "";
                            switch (Number(pid)) {
                                case 1:
                                    _url = "/backend/essay/show";
                                    break;
                                case 2:
                                    _url = "/backend/essay/magic";
                                    break;
                            }
                            window.location.href=_url;
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
                                    <label class="col-sm-3 control-label">标题：</label>
                                    <div class="col-sm-8">
                                        <input id="essay_title" name="essay_title"  type="text" class="form-control" value="{{$info->essay_title}}">
                                    </div>
                                </div>
                                <div class="form-group position">
                                    <label class="col-sm-3 control-label">*显示位置：</label>
                                    <div class="col-sm-8">
                                        <select class="col-sm-12 form-control" id="banner_position_id" name="banner_position_id">
                                            @foreach($position as $val)
                                                <option value="{{$val['id']}}"  @if($info->banner_position_id==$val['id']) selected="selected" @endif>{{$val['position_name']}}</option>
                                            @endforeach
                                        </select>
                                        <span style="color: red" id="tips">如果列表为空请到banner位置列表添加</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">缩率图：</label>
                                    <div class="col-md-8">
                                        <div id="img_info">
                                            <input type="hidden" id="essay_img_info" name="essay_img_info" value="{{$info->essay_img_info}}"/>
                                            <input id="fileupload" name="fileupload" type="file" multiple>
                                            <input type="hidden" name="_token"  value="{{csrf_token()}}"/>
                                            <span class="help-block text_infor">支持文件格式:xls, xlsx, doc, docx, pdf, jpg, png, jpeg。</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">内容</label>
                                    <div class="col-sm-8">
                                        <div class="ibox float-e-margins">
                                            <div class="ibox-content">
                                                <textarea name="essay_content" data-provide="markdown" rows="10" id="essay_content">{{$info->essay_content}}</textarea>
                                            </div>
                                        </div>
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