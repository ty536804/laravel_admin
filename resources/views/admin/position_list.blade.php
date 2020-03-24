@extends('master.base')
@section('title', '管理菜单')
@section("menuname","职位管理")
@section("smallname","职位列表")

@section('css')
    <style>
        /* leftNav */
        #leftNav{
            width: 150px;
            height: 500px;
            padding: 0px 0px 20px 10px;
            overflow: hidden;
            overflow-y: auto;
        }
        #leftNav .tit{
            font-size: 14px;
            font-weight: normal;
            line-height: 30px;
        }
        #leftNav .tit b{
            width: 9px;
            height: 9px;
            background: url('/images/plus.gif') no-repeat;
            margin-right: 5px;
            display: inline-block;
            vertical-align: 1px;
        }
        #leftNav .tit b.cur{
            background: url('/images/minus.gif') no-repeat;
        }
        .leftNav_con div[class^="con"]{
            padding-left: 10px;
            display: none;
            /*position: relative;*/
            /*display: block;*/
            /*padding: 10px 15px;*/
            /*margin-bottom: -1px;*/
            /*background-color: #fff;*/

        }
        .leftNav_con a{
            color: #666;
            font-size: 14px;
            display: block;
            line-height: 24px;
            padding-left: 18px;
            margin-left: -15px;
            text-decoration: none;
        }
        .leftNav_con a:hover{
            background: #f0f0f0;
        }
        .leftNav_con a.cur{
            color: #00dbf5;
        }
        #leftNav .nav_active{
            background: #f0f0f0;
        }
    </style>

@endsection

@section('js')
    <script>
        $(function () {
            //所有部门信息
            var _all= JSON.parse('{!! $position !!}');
            //左侧点击效果
            $('.leftNav_con .tit').click(function(){
                var tt=$(this).attr('data-id');
                var obj='.con'+tt;
                var icon=$(this).find('b');
                if(icon.hasClass('cur')){
                    icon.removeClass('cur');
                    $(this).parent().find(obj).hide();
                }else{
                    icon.addClass('cur');
                    $(this).parent().find(obj).show();
                }
            });
            //左侧点击数据处理
            $('.item').click(function () {
                $('.nav_active').each(function () {
                    if($(this).hasClass('nav_active')){
                        $(this).removeClass('nav_active');
                    }
                });
                $(this).addClass('nav_active');
                var _dept =  _all[$(this).data('parent_id')];
                var _dept =  _dept['position'];
                var _dept =  _dept[$(this).data('id')];
                var power  = _dept.powerid.split("|");
                $('form#edit_power input[type="checkbox"]').prop("checked",false);
                $.each(power,function (index,value) {
                    if(value!=""){
                        $("form#edit_power input[data-id='"+value+"']").prop("checked",true);
                    }
                });
                $('#id').val(_dept.id);
                $('#power_id').val(_dept.powerid);
                $('#desc').val(_dept.desc);
                $('#position_name').val(_dept.position_name);
                $("input:radio[value='"+_dept.status+"']").prop('checked','true');
                $("form#info_form option[value='"+_dept.department_id+"']").prop("selected",true);

            });
            //所有按钮点击效果
            $('#all').on('click',function () {
                var isChecked =   $('form#edit_power input[type="checkbox"]:first').prop("checked");
                $('form#edit_power input[type="checkbox"]').each(function () {
                    $(this).prop("checked",!isChecked);
                });

                setPowerId();
            });
            $('#all_system').on('click',function () {
                var  current =$('#current .active').text();
                var _id ='';
                if(current == '系统'){
                    _id ='#system';
                }else if(current == '简洗'){
                    _id ='#jianxi';
                }else if(current == '快递'){
                    _id ='#kuaidi';
                }else if(current == '工单'){
                    _id ='#gongdan';
                }
                var isChecked =   $(''+_id+' input[type="checkbox"]:first').prop("checked");
                $(''+_id+' input[type="checkbox"]').each(function () {
                    $(this).prop("checked",!isChecked);
                });
                setPowerId();
            });
            //权限 复选框点击效果
            $('form#edit_power input[type="checkbox"]').on('click',function () {
                var _self =  $(this);
                var parentId =  $(this).data("parent_id");
                if(parentId){ //子节点点击
                    if(_self.prop("checked")){ //如果选中 父节点选中
                        $("input[data-id='"+parentId+"']").prop("checked",true);
                    }else{//如果取消选择 父节点取消选中，遍历所有子节点，有选中就重新选中父节点
                        $("input[data-id='"+parentId+"']").prop("checked",false);
                        $("input[data-parent_id='"+parentId+"']").each(function () {
                            if($(this).prop("checked")){
                                $("input[data-id='"+parentId+"']").prop("checked",true);
                                return false;
                            }
                        });
                    }
                }else{ //顶级节点点击
                    //处理子节点
                    $("input[data-parent_id='"+_self.val()+"']").each(function () {
                        $(this).prop('checked',_self.prop("checked"));
                    });
                }
                setPowerId();
            });

            $('#save_info').on('click',function(){
                var id  = $("#id").val();
                var position_name = $("#position_name").val();
                var department_id = $("#department_id").val();
                var desc = $("#desc").val();
                var status = $("input[name='status']:checked").val();
                $.ajax({
                    type:"POST",
                    dataType:"json",
                    url:"{{URL::action('Admin\PositionController@update')}}",
                    data:{"id":id,"position_name":position_name,"department_id":department_id,"desc":desc,"status":status,"_token":'{{ csrf_token()}}'},
                    success: function (result) {
                        if (result.code == "10000") {
                            swal({title:result.msg,type: 'success'},function () {
                                window.location.reload();
                            });
                        } else {
                            swal(result.msg,"",'error');
                        }
                    },
                    error: function (result) {
                        $.each(result.responseJSON.errors, function (k, val) {
                            swal(val[0],"",'error');
                            return false;
                        });
                    }
                });
                return false;
            });

            //权限保存
            $('#save_power').on('click',function(){
                var id  = $("#id").val();
                var powerid = $("#power_id").val();

                $.ajax({
                    type:"POST",
                    dataType:"json",
                    url:"{{URL::action('Admin\PositionController@update')}}",
                    data:{"id":id,"powerid":powerid,"_token":'{{ csrf_token() }}'},
                    success: function (result) {
                        if (result.code == "10000") {
                            // _all[id].powerid = powerid;
                            swal({title:result.msg,type: 'success'},function () {
                                window.location.reload();
                            });
                        } else {
                            swal(result.msg,"",'error');
                        }
                    },
                    error: function (result) {
                        $.each(result.responseJSON.errors, function (k, val) {
                            swal(val[0],"",'error');
                            return false;
                        });
                    }
                });
                return false;
            });
            $('#po_save_info').on('click',function(){
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{{URL::action('Admin\PositionController@save')}}",
                    data: $("#addform").serialize(),
                    success: function (result) {
                        console.log(result)
                        if (result.code == "10000") {
                            swal({title:result.msg,type: 'success'},function () {
                                window.location.reload();
                            });
                        } else {
                            swal(result.msg,"",'error');
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
        });


        function setPowerId(){
            var powerid="|";
            $("#power_id").val(powerid);
            $('form#edit_power [type="checkbox"]').each(function () {
                if($(this).prop("checked")){
                    powerid += $(this).data('id')+"|";
                }
            });
            $("#power_id").val(powerid);
            console.log("选中的权限"+$("#power_id").val());
        }

    </script>
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-3">
                                <div id="leftNav">
                                    <div class="leftNav_con">
                                        @foreach($position as $v)
                                            <div class="leftNav_con">
                                                <div class="tit" data-id={{$v->id}}>
                                                    <b></b>{{$v->dp_name}}
                                                </div>
                                                @if(!empty($v->position))
                                                    <div class="con{{$v->id}} ">
                                                        @foreach($v->position as $i=>$o)
                                                            <a href="javascript:void(0);" class="item" data-parent_id="{{$v->id}}" data-id={{$i}}>{{$o->position_name}}</a>
                                                        @endforeach
                                                    </div>

                                                @endif
                                            </div>
                                        @endforeach


                                        {{--@foreach($position as $v)--}}
                                            {{--<a href="javascript:void(0);" class="item" data-id={{$v->id}}>{{$v->position_name}}</a>--}}
                                        {{--@endforeach--}}
                                    </div>

                                </div>
                            </div>
                            <div class="col-xs-9">
                                <div class="nav-tabs-custom">
                                    <!-- Tabs within a box -->
                                    <ul class="nav nav-tabs pull-left">
                                        <li class="active"><a href="#dept_edit" data-toggle="tab">职位信息</a></li>
                                        <li><a href="#dept_power" data-toggle="tab">职位权限</a></li>
                                        <button type="button" id="btn" class="btn btn-primary" data-toggle="modal" data-target="#myModal2">
                                            新增职位
                                        </button>
                                    </ul>
                                    <div class="tab-content no-padding">
                                        @include('component.admin.position_edit')
                                        @include('component.admin.department_power')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal inmodal" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" class="close_modal">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">用户管理</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal m-t" id="addform">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label class="col-sm-3 control-label">* 职位名称：</label>
                            <div class="col-sm-8">
                                <input id="position_name" name="position_name"  type="text" class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">* 归属部门：</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="department_id" id="department_id">
                                    <option value="">--请选择--</option>
                                    @foreach($department as $k=>$v)
                                        <option value="{{$v->id}}">{{$v->dp_name}} </option>
                                        @foreach($v->children as $z)
                                            <option value="{{$z->id}}">&nbsp;&nbsp;&nbsp;&nbsp;{{$z->dp_name}}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">* 职位说明：</label>
                            <div class="col-sm-8">
                                <textarea id="desc" name="desc" class="form-control" required="" aria-required="true"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">* 状态：</label>
                            <div class="col-sm-8 ">
                                <label>
                                    <input type="radio" value="1" name="status" id="status">正常</label>
                                <label>
                                    <input type="radio" value="0" name="status" id="status"> 禁用</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" id="po_save_info" name="po_save_info">提交</button>
                    <button type="button" class="btn btn-white close_modal" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
@endsection