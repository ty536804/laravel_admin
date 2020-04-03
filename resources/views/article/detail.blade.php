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
            let file = new upload({ uid:uid,pictype:1001,uploadid:"fileupload",tag:"thumb_img_info",token:'<?php echo e(csrf_token());?>'});
            file.initUpload('{!! $info->thumb_img_info !!}','{{asset('storage/uploadfile/').'/'}}') ;
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
            if ($.trim($("#title").val()) == "") {
                sweetAlert("操作失败","标题不能为空",'error');
                return false;
            }

            if ($.trim($("#summary").val()) == "") {
                sweetAlert("操作失败","摘要不能为空",'error');
                return false;
            }

            if ($.trim($("#admin").val()) == "") {
                sweetAlert("操作失败","编辑者不能为空",'error');
                return false;
            }

            if ($.trim($("#content").val()) == "") {
                sweetAlert("操作失败","内容不能为空",'error');
                return false;
            }

            if ($.trim($("#thumb_img_info").val()) == "") {
                sweetAlert("操作失败","请上传图片",'error');
                return false;
            }

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{URL::action('Backend\ArticleController@articleSave')}}",
                data: $("#addform").serialize(),
                success: function (result) {
                    if (result.code == "10000") {
                        swal({title:result.msg,type: 'success'},
                        function () {
                            window.location.href="/backend/article/show";
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
                                        <input id="title" name="title"  type="text" class="form-control" value="{{$info->title}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">摘要：</label>
                                    <div class="col-sm-8">

                                        <input id="summary" name="summary"  type="text" class="form-control" value="{{$info->summary}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">编辑者：</label>
                                    <div class="col-sm-8">
                                        <input id="admin" name="admin"  type="text" class="form-control" value="{{$info->admin}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">来源：</label>
                                    <div class="col-sm-8">
                                        <input id="com" name="com"  type="text" class="form-control" value="{{$info->com}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">缩率图：</label>
                                    <div class="col-md-8">
                                        <div id="img_info">
                                            <input type="hidden" id="thumb_img_info" name="thumb_img_info" value="{{$info->thumb_img_info}}"/>
                                            <input id="fileupload" name="fileupload" type="file" multiple>
                                            <input type="hidden" name="_token"  value="{{csrf_token()}}"/>
                                            <span class="help-block text_infor">支持文件格式:xls, xlsx, doc, docx, pdf, jpg, png, jpeg。</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">是否热点</label>
                                    <div class="col-sm-8">
                                        <select class="col-sm-12 form-control" id="hot" name="hot">
                                            <option value="1" @if($info->hot==1) selected @endif>是</option>
                                            <option value="2" @if($info->hot==2) selected @endif>否</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">优先级</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="sort" name="sort" class="col-sm-12 form-control" value="{{$info->sort}}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">是否显示</label>
                                    <div class="col-sm-8">
                                        <select class="col-sm-12 form-control" id="sort" name="sort">
                                            <option value="1" @if($info->is_show==1) selected @endif>显示</option>
                                            <option value="2" @if($info->is_show==2) selected @endif>隐藏</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">内容</label>
                                    <div class="col-sm-8">
                                        <div class="ibox float-e-margins">
                                            <div class="ibox-content">
                                                <textarea name="content" data-provide="markdown" rows="10" id="content">{{$info->content}}</textarea>
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