/***
 * @name 全选 ()
 * @param:selectSingle 需要选择的name 值
 * @param:checkAll 全选按钮id
 * @param:inputAppend 追加到input框的id值
 * @example 城市管理 广告设置
 * @author: al
 * 
 */
function checkAll(selectSingle,checkAll,inputAppend) {
    $('#'+checkAll).on('click',function () {
        var $items=$(':checkbox[name="'+selectSingle+'"]');
        var couriers = [];
        if ($('#'+checkAll).is(':checked')){
            $items.prop('checked', true);
            $items.filter(':checked').each(function () {
                couriers.push(this.value);
            })
            couriers = couriers.join();
            $('#'+inputAppend).val(couriers);
        } else
        {
            $(':checkbox[name="'+selectSingle+'"]').prop('checked', false);
            $('#'+inputAppend).val("");
        }
    });
    $(document).on('click','input[name="'+selectSingle+'"]',function () {
        var couriers = [];
        $('input[name="'+selectSingle+'"]').each(function () {
            if($(this).is(':checked')){
                couriers.push(this.value);
            }
        });
        if($('input[name="'+selectSingle+'"]:checked').length == $('input[name="'+selectSingle+'"]').length) {
            $('#'+checkAll).prop('checked',true);
        } else {
            $('#'+checkAll).prop('checked',false);
        }
        couriers = couriers.join();
        $('#'+inputAppend).val(couriers);
    });
}
// /***
//  * @name 全选 (角色与权限)
//  * @param: rolePowerAll 全选按钮
//  *         checkAll 追加到文本框的id值
//  * @example 众包 公告操作
//  * @author: al
//  *
//  */
// function checkRolePower(rolePowerAll,roles,powers) {
//     $('#'+rolePowerAll).on('click',function () {
//         if ($('#'+rolePowerAll).is(':checked')) {
//             $('.'+roles).prop('checked', true);
//             $('.'+power).prop('checked', true);
//         }else {
//             $('.'+roles).prop('checked', false);
//             $('.'+power).prop('checked', false);
//         }
//     });
//     $(document).on('click','input[class="'+roles+'"]',function () {
//         if($('input[class="'+roles+'"]:checked').length ==$('input[class="'+roles+'"]').length) {
//             $('#'+rolePowerAll).prop('checked',true);
//         } else {
//             $('#'+rolePowerAll).prop('checked',false);
//         }
//     });
//     $('#power').on('click',function () {
//         if($('input[class="'+powers+'"]:checked').length ==$('input[class="'+powers+'"]').length) {
//             $('#'+rolePowerAll).prop('checked',true);
//         } else {
//             $('#'+rolePowerAll).prop('checked',false);
//         }
//     });
// }
/**
 * 分割
 * data 当前拥有的值 列 -1|1|2|3|4
 * arr 所有的值 {0:"分割",-1: "阿萨德",1: "张三", 2: "李四", 3: "王五", 4: "赵六"}
 * */
function division(data,arr) {
    var str =[], brr = data.split('|');
    for (var i=0;i<brr.length;i++){
        for (var k in arr){
            if (brr[i] ==k){
                str.push(arr[k]);
            }
        }
    }
    str.join();
    return str;
}

/**
 * 确认框
 * utl 路径
 * data 传入数据 {1: "张三", 2: "李四", 3: "王五", 4: "赵六"}
 * */
function isConfirm(url,data) {
    swal({
        title: "确定要执行这个操作吗？",
        // text: "删除后可就无法恢复了。",
        type: "warning",
        showCancelButton: true,
        closeOnConfirm: false,
        confirmButtonText: "是的，我要执行！",
        confirmButtonColor: "#ec6c62",
        cancelButtonText: "容我三思"
    }, function (isConfirm) {
        if (!isConfirm) return;
        $.ajax({
            type:"POST",
            dataType:"json",
            url: url,
            data:data,
            success: function (result) {
                if (result.code == "10000") {
                    swal({title:result.msg,type: 'success'},
                        function () {
                            myTable.ajax.reload(null,false);
                        });
                } else {
                    swal({title:result.msg,type: 'error'});
                }
            },
            error: function (result) {
                swal({title:"网络错误",type: 'error'});
            }
        });
    });
}
