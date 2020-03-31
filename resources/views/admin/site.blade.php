@extends('master.base')
@section('title', '图片管理')
@section("menuname",'配置信息')
@section("smallname","基本配置")

@section('css')
    <link href="{{asset('/admin/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}" rel="stylesheet">
    {{--文本编辑器--}}
    <link rel="stylesheet" type="text/css" href="{{asset("/plugins/markdown/bootstrap-markdown.min.css")}}" />
    @endsection

    @section('js')
    {{--文本编辑器--}}
    <script type="text/javascript" src="{{asset("/plugins/markdown/js/markdown.js")}}"></script>
    <script type="text/javascript" src="{{asset("/plugins/markdown/js/to-markdown.js")}}"></script>
    <script type="text/javascript" src="{{asset("/plugins/markdown/js/bootstrap-markdown.js")}}"></script>
    <script type="text/javascript" src="{{asset("/plugins/markdown/js/bootstrap-markdown.zh.js")}}"></script>
    <script>
        $(document).on('click','#button_id',function () {
            if ($.trim($("#site_title").val()) == "") {
                sweetAlert("操作失败","网站标题不能为空",'error');
                return false;
            }

            if ($.trim($("#site_desc").val()) == "") {
                sweetAlert("操作失败","网站描述不能为空",'error');
                return false;
            }

            if ($.trim($("#site_keyboard").val()) == "") {
                sweetAlert("操作失败","网站关键字不能为空",'error');
                return false;
            }

            if ($.trim($("#site_copyright").val()) == "") {
                sweetAlert("操作失败","网站版权不能为空",'error');
                return false;
            }

            if ($.trim($("#site_tel").val()) == "") {
                sweetAlert("操作失败","联系电话不能为空",'error');
                return false;
            }

            if ($.trim($("#site_email").val()) == "") {
                sweetAlert("操作失败","联系邮箱不能为空",'error');
                return false;
            }

            if ($.trim($("#site_address").val()) == "") {
                sweetAlert("操作失败","联系地址不能为空",'error');
                return false;
            }

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{URL::action('Admin\SiteController@siteSave')}}",
                data: $("#addform").serialize(),
                success: function (result) {
                    if (Number(result.code) == 10000) {
                        swal({title:result.msg,type: 'success'},
                        function () {
                            window.location.href="/admin/site/show";
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
                                <input id="id" name="id" type="hidden" value="{{$info->id}}">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">网站标题：</label>
                                    <div class="col-sm-8">
                                        <input id="site_title" name="site_title"  type="text" class="form-control" value="{{$info->site_title}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">网站描述：</label>
                                    <div class="col-sm-8">
                                        <div class="ibox float-e-margins">
                                            <div class="ibox-content">
                                                <textarea name="site_desc" data-provide="markdown" rows="8" id="site_desc">{{$info->site_desc}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">网站关键字：</label>
                                    <div class="col-sm-8">
                                        <div class="ibox float-e-margins">
                                            <div class="ibox-content">
                                                <textarea name="site_keyboard" data-provide="markdown" rows="8" id="site_keyboard">{{$info->site_keyboard}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">网站版权：</label>
                                    <div class="col-sm-8">
                                        <input id="site_copyright" name="site_copyright"  type="text" class="form-control" value="{{$info->site_copyright}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">联系电话：</label>
                                    <div class="col-sm-8">
                                        <input id="site_tel" name="site_tel"  type="text" class="form-control" value="{{$info->site_tel}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">联系邮箱：</label>
                                    <div class="col-sm-8">
                                        <input id="site_email" name="site_email"  type="text" class="form-control" value="{{$info->site_email}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">联系地址：</label>
                                    <div class="col-sm-8">
                                        <input id="site_address" name="site_address"  type="text" class="form-control" value="{{$info->site_address}}">
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