//物流状态
function logisticsInfo(row,data,meta)
{
    let str = '',_html = '',_cancel='';
    let cancel_role = row.logistics ? row.logistics.cancel_role : 0;
    switch (Number(data)) {
        case 0:
            str = '调度未确认';
            _html = '<br/><span class="btn btn-sm btn-danger" onclick="cancel_order('+meta.row+')">取消订单</span>';
            break;
        case 1:
            str = '物流已取';
            break;
        case 2:
            str = '物流签收送件';
            break;
        case 3:
            str = '已签收';
            break;
        case 4:
            str = '加工店已签收';
            break;
        case 5:
            str = '加工店分拣完成';
            break;
        case 6:
            str = '加工店上架';
            break;
        case 7:
            str = '收衣店签收';
            break;
        case 8:
            str = '用户送到收衣店';
            break;
        case 9:
            str = '调度取派单';
            _html = '<br/><span class="btn btn-sm btn-danger " onclick="cancel_order('+meta.row+')">取消订单</span>';
            break;
        case 10:
            str = '订单取消';
            switch (Number(cancel_role)) {
                case 1:
                    _cancel = '<br/><label class="label label-danger">&nbsp;客户取消</label>';
                    break;
                case 2:
                    _cancel = '<br/><label class="label label-danger">&nbsp;加工店取消</label>';
                    break;
                case 3:
                    _cancel = '<br/><label class="label label-danger">&nbsp;快递取消</label>';
                    break;
                case 4:
                    _cancel = '<br/><label class="label label-danger">&nbsp;客服取消</label>';
                    break;
            }
            break;
        case 11:
            str = '调度已确认';
            _html = '<br/><span class="btn btn-sm btn-danger" onclick="cancel_order('+meta.row+')">取消订单</span>';
            break;
        case 19:
            str = '客户未评价(已过期)';
            break;
        case 18:
            str = '客户已评价';
            break;
        case 20:
            str = '物流取送达';
            break;
        case 21:
            str = '物流送签收';
            break;
        case 17:
            str = '送件快递员已签收';
            break;
        case 16:
            str = '送件待快递员签收';
            break;
        case 15:
            str = '调度送派单';
            break;
        case -1:
            str = '物流取退回';
            break;
        case -2:
            str = '物流送退回';
            break;
    }
    return '<a href="/order/orderDetail?order_sn='+row.order_sn+'" class="label label-primary">'+str+'</a>'+_html+_cancel+'';
}

function payInfo(data) {
    var str = '';
    let _html = '';
    switch (Number(data)) {
        case 0:
            str = '未支付';
            break;
        case 1:
            // if (Number(logistics) ==0) {
            //      _html = '<br/><span class="btn btn-sm btn-danger" onclick="cancel_logistics('+meta.row+')">呼叫快递</span>';
            // }
            str = '已支付';
            break;
        case 2:
            str = '待支付';
            break;
        case 4:
            str = '支付中';
            break;
        case 3:
            str = '已退款';
            break;
        case 5:
            str = '支付中';
            break;
        case 6:
            str = '部分退款';
            break;
        case 10:
            str = '补差价未支付';
            break;
        case 11:
            str = '补差价已支付';
            break;
        case 14:
            str = '补差价继续支付';
            break;
    }
    return '<label class="label label-warning">'+str+'</label>'+_html;
}