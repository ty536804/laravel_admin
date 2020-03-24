/**
 * 地图
 * 根据地址选择位置
 * ditu
 * */


lng = parseFloat(document.getElementById("outlet_form_lng").value);
console.log(lng);
lat = parseFloat(document.getElementById("outlet_form_lat").value);

$("#set_move").click(function(){
    // document.getElementById("set_move").style.background="green";
    draggable[1](true);
    $(this).attr('disabled', true);
    $("#set_move_disable").attr('disabled', false);
});
$("#set_move_disable").click(function () {
    draggable[1](false);
    $("#set_move").attr('disabled', false);
    $(this).attr('disabled', true);
});
$("#get_pos").click(function () {
        var detailedAddr =  new String(document.getElementById("address").value);

        if(!detailedAddr || detailedAddr == "" || detailedAddr.replace(/(^\s*)|(\s*$)/g,"") == "")
        {
            alert('请输入详细地址');
            return false;
        }
        var curAddress = new String(document.getElementById("city_id").value);
        curAddress += document.getElementById("area_id").value;
        curAddress += document.getElementById("address").value;  //获取定位地址
        searchPlace(curAddress);
        draggable[1](false);
        $("#set_move").attr('disabled', false);
        $("#set_move_disable").attr('disabled', true);

        $("#search_tip").css("display", "none");
        $("#search_tip").html("");
        $("#keyword").val("");
    }
);
//地图
var mapObj = new AMap.Map("outlet_map_container", {
    resizeEnable: true,
    view: new AMap.View2D({
        zoom: 25 //地图显示的缩放级别
    })
});
$('.amap-logo').remove();
$('.amap-copyright').remove();
if(lng && lat && (lng !== 0 && lat !== 0))
{
    mapObj.setCenter(new AMap.LngLat(lng, lat));
    lnglat = new AMap.LngLat(lng, lat);
    geocoder(lnglat);
    $("#set_move").attr('disabled', false);
    $("#set_move_disable").attr('disabled', true);
}
else{
    var cityName= new String(document.getElementById("city_id").value);
    if(!cityName)
    {
        cityName = "北京";
    }
    mapObj.setCity(cityName);
    $("#set_move").attr('disabled', true);
    $("#set_move_disable").attr('disabled', true);
}
AMap.event.addListener(mapObj, 'click', function (e) {
    $("#search_tip").curSelect = -1;
    $("#search_tip").hide();
});

$("#keyword").focus(function () {
    $("#search_tip").show();
});

$("#search_tip").curSelect = -1;
//输入提示
function autoSearch() {
    var keywords = document.getElementById("keyword").value;
    var auto;

    //加载输入提示插件
    AMap.service(["AMap.Autocomplete"], function () {
        var autoOptions = {
            city: "" //城市，默认全国
        };
        auto = new AMap.Autocomplete(autoOptions);
        //查询成功时返回查询结果
        if (keywords.length > 0) {
            auto.search(keywords, function (status, result) {
                autocomplete_CallBack(result);
                document.getElementById("search_tip").style.display = "block";
            });
        }
        else {
            document.getElementById("search_tip").innerHTML = "";
            document.getElementById("search_tip").curSelect = -1;
            document.getElementById("search_tip").style.display = "none";
        }
    });
}

//输出输入提示结果的回调函数
function autocomplete_CallBack(data) {
    var resultStr = "";
    var tipArr = data.tips;
    if (tipArr && tipArr.length > 0) {
        for (var i = 0; i < tipArr.length; i++) {
            resultStr += "<div id='divid" + (i + 1) + "' onmouseover='openMarkerTipById(" + (i + 1)
                + ",this)' onclick='selectResult(" + i + ")' onmouseout='onmouseout_MarkerStyle(" + (i + 1)
                + ",this)' style=\"font-size: 13px;cursor:pointer;padding:5px 5px 5px 5px;\"" + "data=" + tipArr[i].adcode + ">" + tipArr[i].name + "<span style='color:#C1C1C1;'>" + tipArr[i].district + "</span></div>";
        }
    }
    else {
        resultStr = " π__π 亲,人家找不到结果!<br />要不试试：<br />1.请确保所有字词拼写正确<br />2.尝试不同的关键字<br />3.尝试更宽泛的关键字";
    }
    document.getElementById("search_tip").curSelect = -1;
    document.getElementById("search_tip").tipArr = tipArr;
    document.getElementById("search_tip").innerHTML = resultStr;
    document.getElementById("search_tip").style.display = "block";
}
//输入提示框鼠标滑过时的样式
function openMarkerTipById(pointid, thiss) {  //根据id打开搜索结果点tip
    thiss.style.background = '#CAE1FF';
}

//输入提示框鼠标移出时的样式
function onmouseout_MarkerStyle(pointid, thiss) {  //鼠标移开后点样式恢复
    thiss.style.background = "";
}

//从输入提示框中选择关键字并查询
function selectResult(index) {
    if (index < 0) {
        return;
    }
    if (navigator.userAgent.indexOf("MSIE") > 0) {
        document.getElementById("keyword").onpropertychange = null;
        document.getElementById("keyword").onfocus = focus_callback;
    }
    //截取输入提示的关键字部分
    var text = document.getElementById("divid" + (index + 1)).innerHTML.replace(/<[^>].*?>.*<\/[^>].*?>/g, "");
    var cityCode = document.getElementById("divid" + (index + 1)).getAttribute('data');
    document.getElementById("keyword").value = text;
    document.getElementById("search_tip").style.display = "none";
    //根据选择的输入提示关键字查询
    mapObj.plugin(["AMap.PlaceSearch"], function () {
        var msearch = new AMap.PlaceSearch();  //构造地点查询类
        AMap.event.addListener(msearch, "complete", placeSearch_CallBack); //查询成功时的回调函数
        msearch.setCity(cityCode);
        msearch.search(text);  //关键字查询查询
    });
}
function searchPlace(text) {
    document.getElementById("search_tip").style.display = "none";
    mapObj.plugin(["AMap.PlaceSearch"], function () {
        var msearch = new AMap.PlaceSearch();  //构造地点查询类
        AMap.event.addListener(msearch, "complete", placeSearch_CallBack); //查询成功时的回调函数
        msearch.search(text);  //关键字查询查询
    });
}

//定位选择输入提示关键字
function focus_callback() {
    if (navigator.userAgent.indexOf("MSIE") > 0) {
        document.getElementById("keyword").onpropertychange = autoSearch;
    }
}
//输出关键字查询结果的回调函数
function placeSearch_CallBack(data) {
    //清空地图上的InfoWindow和Marker
    var poiArr = data.poiList.pois;
    if (poiArr.length > 0) {
        mapObj.clearMap();
        addmarker(poiArr[0].name, poiArr[0].location);
        mapObj.setCenter(poiArr[0].location);
        document.getElementById("keyword").style.borderColor = "#ccc";
        $("#set_move").attr('disabled', false);
        $("#set_move_disable").attr('disabled', true);
        draggable[1](false);
    } else {
        document.getElementById("keyword").style.borderColor = "red";
        alert("该地址搜索不到，请修改搜索关键词");
    }
}

var marker_getter;

function draggable_container() {
    var draggable = false;
    var is_draggable = function () {
        return draggable;
    }
    var set_draggable = function (is_draggable) {
        draggable = is_draggable;
        if (marker_getter) {
            marker_getter().setDraggable(draggable);
        }
    }

    return [is_draggable, set_draggable];
}

var draggable = draggable_container();


//添加查询结果的marker&infowindow
function addmarker(name, loc) {
    var lngX = loc.getLng();
    var latY = loc.getLat();
    update_pos_now(lngX,latY);

    var markerOption = {
        map: mapObj,
        position: new AMap.LngLat(lngX, latY),
        draggable: draggable[0](),
        cursor: 'move'
    };

    var marker = new AMap.Marker(markerOption);

    var infoWindow = new AMap.InfoWindow({
        size: new AMap.Size(300, 0),
        autoMove: true,
        offset: new AMap.Pixel(0, -30),
        content: "<h5><font color=\"#00a6ac\">  " + name + "</font></h5>"
    });
    infoWindow.open(mapObj, marker.getPosition());

    var updateMarkInfo = function (e) {
        infoWindow.open(mapObj, marker.getPosition());
        update_pos_now(marker.getPosition().lng, marker.getPosition().lat);
        geocoder(new AMap.LngLat(marker.getPosition().lng, marker.getPosition().lat), infoWindow);

    };
    marker_getter = function () {
        return marker;
    }
    AMap.event.addListener(marker, "dragend", updateMarkInfo);
    AMap.event.addListener(marker, "dragstart", function () {
        infoWindow.close();
    });
    mapObj.setFitView();
}

function update_pos_now(lng, lat) {
    var lngstr = lng.toString();
    var latstr = lat.toString();
    document.getElementById("outlet_form_lng").value = lngstr;
    document.getElementById("outlet_form_lat").value = latstr;
}

function keydown(event) {
    var key = (event || window.event).keyCode;
    var result = document.getElementById("search_tip");
    var cur = result.curSelect;
    if (key === 40) {//down
        if (cur + 1 < result.childNodes.length) {
            if (result.childNodes[cur]) {
                result.childNodes[cur].style.background = '';
            }
            result.curSelect = cur + 1;
            result.childNodes[cur + 1].style.background = '#CAE1FF';
            document.getElementById("keyword").value = result.tipArr[cur + 1].name;
            return true;
        }
    } else if (key === 38) {//up
        if (cur - 1 >= 0) {
            if (result.childNodes[cur]) {
                result.childNodes[cur].style.background = '';
            }
            result.curSelect = cur - 1;
            result.childNodes[cur - 1].style.background = '#CAE1FF';
            document.getElementById("keyword").value = result.tipArr[cur - 1].name;
        }
    } else if (key === 13 || key === 10) {
        var res = document.getElementById("search_tip");

        if (res && res['curSelect'] !==null && res['curSelect'] !==undefined && res['curSelect'] >= 0) {
            selectResult(document.getElementById("search_tip").curSelect);
        } else if (document.getElementById("keyword").value.trim().length > 0) {
            searchPlace(document.getElementById("keyword").value);
        }
        event.preventDefault();
    }


}

function geocoder(lnglatXY, infowindow) {
    var MGeocoder;
    //加载地理编码插件
    AMap.service(["AMap.Geocoder"], function () {
        MGeocoder = new AMap.Geocoder({
            radius: 10,
            extensions: "all"
        });
        //逆地理编码
        MGeocoder.getAddress(lnglatXY, function (status, result) {
            if (status === 'complete' && result.info === 'OK') {
                var address = result.regeocode.formattedAddress;
                if (infowindow != null) {
                    infowindow.setContent("<h5><font color=\"#00a6ac\">  " + address + "</font></h5>");
                } else {
                    addmarker(address, lnglatXY);
                }
            }
        });
    });
}
