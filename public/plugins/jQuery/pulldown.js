
//Regional开始
$(document).ready(function(){
    $(".Regional").click(function(){
        if ($('.grade-eject').hasClass('grade-w-roll')) {
            $('.grade-eject').removeClass('grade-w-roll');
			$(this).removeClass('current');
			$('.screening').attr('style',' ');
        } else {
            $('.grade-eject').addClass('grade-w-roll');
			$(this).addClass('current');
			$(".Sort").removeClass('current');
			//$('.screening').attr('style','position: absolute;top:50;');
        }
    });
});

$(document).ready(function(){
    var $div_li =$(".grade-eject ul li");
	$div_li.click(function(){
    	var index = $div_li.index(this);
    	$(".grade-t").eq(index)
    		.css("left","20%")
        .siblings(".grade-t").css("left","100%");
	});
});

$(document).ready(function(){
    $(".Regional").click(function(){
        if ($('.Sort-eject').hasClass('grade-w-roll')){
            $('.Sort-eject').removeClass('grade-w-roll');
        };
    });
});

$(document).ready(function(){
    $(".Sort").click(function(){
        if ($('.Sort-eject').hasClass('grade-w-roll')) {
            $('.Sort-eject').removeClass('grade-w-roll');
            $(this).removeClass('current');
            $('.screening').attr('style',' ');
        } else {
            $('.Sort-eject').addClass('grade-w-roll');
            $(this).addClass('current');
            $(".Regional").removeClass('current');
            //$('.screening').attr('style','position: absolute;top:50;');
        }
    });
});
$(document).ready(function(){
    $(".Sort").click(function(){
        if ($('.grade-eject').hasClass('grade-w-roll')){
            $('.grade-eject').removeClass('grade-w-roll');
        };
    });
});
//js点击事件监听开始

function grade1(wbj){
    var cname = $(wbj).text();
    $('#cname').text(cname);
    var aid = $(wbj).attr('aid');
    $('#aid').val(aid);
    var arr = document.getElementById("gradew").getElementsByTagName("li");
    for (var i = 0; i < arr.length; i++){
        var a = arr[i];
        a.style.background = "";
    };
    wbj.style.background = "#eee";
}

