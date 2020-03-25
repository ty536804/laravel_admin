@extends('master.base')
@section('title', '管理菜单')
@section("menuname","部门管理")
@section("smallname","部门列表")

@section('css')
<style>

    #leftNav{
        width: 110%;
        padding: 0px 0px 20px 0px;
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
        display: inline-block;
        vertical-align: 1px;
    }
    #leftNav .tit b.cur{
        background: url('/images/minus.gif') no-repeat;
    }
    .leftNav_con {
       width: 100%;
       margin: auto;
    }
    .leftNav_con a{
        color: #666;
        font-size: 14px;
        display: block;
        line-height: 24px;
        text-decoration: none;
    }
    .leftNav_con a:hover{
        background: #f0f0f0;
        color: #71A2E3;
    }
    .leftNav_con a.cur{
        /*width: 110%;*/
        color: #00dbf5;
    }
    #leftNav .nav_active{
        background: #f0f0f0;
        color: #71A2E3;
    }
    #leftNav dd{ padding-left:15%;}
    #leftNav dt{ width:120%;}
    #leftNav dt a{display:block;padding-left:25%;line-height:35px;cursor:pointer;position:relative;font-weight:normal}
    .custom_scrollbar {
        overflow: hidden; }
    .custom_scrollbar::-webkit-scrollbar {
        width: 4px; }
    .custom_scrollbar::-webkit-scrollbar-track {
        background: #fff; }
    .custom_scrollbar::-webkit-scrollbar-thumb {
        background-color: #4a89dc; }
    .custom_scrollbar:hover {
        overflow-y: auto;
        overflow-x: hidden;}
</style>

@endsection

@section('js')
<script>
    $(function () {
        //所有部门信息
        var _all= JSON.parse('{!! $all !!}');
        //左侧点击效果
        $('.leftNav_con .tit').click(function(){
            var tt=$(this).attr('data-id');
            var obj='.con'+tt;
            var icon=$(this).find('b');
            if(icon.hasClass('cur')){
                icon.removeClass('cur');
                $(this).parent().find(obj).slideUp();
            }else{
                icon.addClass('cur');
                $(this).parent().find(obj).slideDown();
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
            var _dept =  _all[$(this).data('id')];
            var power  = _dept.powerid.split("|");

            $('form#edit_power input[type="checkbox"]').prop("checked",false);
            $.each(power,function (index,value) {
                if(value!=""){
                    $("form#edit_power input[data-id='"+value+"']").prop("checked",true);
                }
            });
            $('#id').val(_dept.id);
            $('#power_id').val(_dept.powerid);
            $('#dp_name').val(_dept.dp_name);
            $("input:radio[value='"+_dept.status+"']").prop('checked',true);
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

        $('#save_info').on('click',function(){
            var id  = $("#id").val();
            var dp_name = $("#dp_name").val();
            var status = $("input[name='status']:checked").val();
            $.ajax({
                type:"POST",
                dataType:"json",
                url:"{{URL::action('Admin\DepartmentController@update')}}",
//                data:$("#info_form").serialize(),
                data:{"id":id,"dp_name":dp_name,"status":status,"_token":'{{ csrf_token()}}'},
                success: function (result) {
                    if (result.code == "10000") {
//                        _all[id].powerid = powerid;
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

        //权限保存
         $('#save_power').on('click',function(){
             var id  = $("#id").val();
             var powerid = $("#power_id").val();
              $.ajax({
                 type:"POST",
                 dataType:"json",
                 url:"{{URL::action('Admin\DepartmentController@update')}}",
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
                            <div class="col-xs-3 panel-group custom_scrollbar"  id="accordion" >
                                <div id="leftNav" >
                                        @foreach($department as $v)
                                        <dl class="leftNav_con" >
                                            <dd  class="tit item" data-id={{$v->id}} >
                                                <b></b> {{$v->dp_name}}
                                            </dd>
                                            @if(!empty($v->children))
                                                    <dt  class="con{{$v->id}} " id="con{{$v->id}}"  style="display: none;">
                                                        @foreach($v->children as $o)
                                                            <a href="javascript:void(0);"  class="item" data-id={{$o->id}}>{{$o->dp_name}}</a>
                                                        @endforeach
                                                    </dt>

                                            @endif
                                        </dl>
                                        @endforeach
                                </div>
                            </div>
                            <div class="col-xs-9">
                                <div class="nav-tabs-custom">
                                    <!-- Tabs within a box -->
                                    <ul class="nav nav-tabs pull-left">
                                        <li class="active"><a href="#dept_edit" data-toggle="tab">部门信息</a></li>
                                        <li><a href="#dept_power" data-toggle="tab">部门权限</a></li>
                                    </ul>
                                    <div class="tab-content no-padding">
                                        @include('component.admin.department_edit')
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