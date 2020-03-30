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
            position: relative;
            display: block;
            padding: 10px 15px;
            margin-bottom: -1px;
            background-color: #fff;

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
            //左侧点击数据处理
            $('.item').click(function () {
                $('.nav_active').each(function () {
                    if($(this).hasClass('nav_active')){
                        $(this).removeClass('nav_active');
                    }
                });
                $(this).addClass('nav_active');
                var _dept =  _all[$(this).data('id')];
                var power  = _dept.powerid.split("|");
                $('form#edit_power input[type="checkbox"]').prop("checked",false);
                $.each(power,function (index,value) {
                    if(value!=""){
                        $("form#edit_power input[data-id='"+value+"']").prop("checked",true);
                    }
                });
                $('#info_form #id').val(_dept.id);
                $("#edit_power #id").val(_dept.id);
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

            /**
             * 职位信息保存
             */
            $('#save_info').on('click',function(){
                let position_name = $.trim($("#info_form #position_name").val());
                let department_id = $.trim($("#department_id").val());
                let desc = $("#desc").val();
                if (position_name =="") {
                    swal("操作失败","职位名称不能为空","error");
                    return false;
                }

                if (department_id =="" || department_id<1) {
                    swal("操作失败","选择归属部门","error");
                    return false;
                }

                if (desc =="") {
                    swal("操作失败","职位描述不能为空","error");
                    return false;
                }

                $.ajax({
                    type:"POST",
                    dataType:"json",
                    url:"{{URL::action('Admin\PositionController@update')}}",
                    data:$("#info_form").serialize(),
                    success: function (result) {
                        if (result.code == "10000") {
                            swal({title:result.msg,type: 'success'},
                                function () {
                                    window.location.reload();
                                });
                        } else {
                            swal("操作失败",result.msg,'error');
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
                let id  = $("#edit_power #id").val();
                let powerid = $("#power_id").val();
                $.ajax({
                    type:"POST",
                    dataType:"json",
                    url:"{{URL::action('Admin\PositionController@positionAdd')}}",
                    data:{"id":id,"powerid":powerid,"_token":'{{ csrf_token() }}'},
                    success: function (result) {
                        if (result.code == "10000") {
                            _all[id].powerid = powerid;
                            swal({title:result.msg,type: 'success'});
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

        });

        /**
         * 职位权限 点击事件
         */
        function setPowerId(){
            var powerid="|";
            $("#power_id").val(powerid);
            $('form#edit_power [type="checkbox"]').each(function () {
                if($(this).prop("checked")){
                    powerid += $(this).data('id')+"|";
                }
            });
            $("#power_id").val(powerid);
            // console.log("选中的权限"+$("#power_id").val());
        }

        /**
         * 新增职位
         */
        $('.position').on('click',function () {
            $('.leftNav_con a').removeClass("nav_active");
            $("#info_form #desc").val("");
            $("#info_form #department_id").val(0);
            $("#info_form #position_name").val("");
            $('#info_form #id').val("");
        });

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
                                    <label class="position">职位</label>
                                    <div class="leftNav_con">
                                        @foreach($position as $v)
                                        <a href="javascript:void(0);" class="item" data-id={{$v->id}}>{{$v->position_name}}</a>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                            <div class="col-xs-9">
                                <div class="nav-tabs-custom">
                                    <!-- Tabs within a box -->
                                    <ul class="nav nav-tabs pull-left">
                                        <li class="active"><a href="#dept_edit" data-toggle="tab">职位信息</a></li>
                                        <li><a href="#dept_power" data-toggle="tab">职位权限</a></li>
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
@endsection