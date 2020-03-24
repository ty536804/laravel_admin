@extends('master.base')
@section('title', '管理菜单')
@section("menuname","权限管理")
@section("smallname","权限管理")

@section('css')

@endsection

@section('js')
    <script type="text/javascript">
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
        })
        $(function () {
            var windowHeight  = $(window).height();
            $('#leftNav').css("height",windowHeight-280);
            console.info(windowHeight);

            var data = JSON.parse('{!! $data !!}');

            console.info(data);
        });
        $(window).resize(function(){
            var windowHeight  = $(window).height();

            $('#leftNav').css("height",windowHeight-280);
        });
    </script>

@endsection


@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-default">
                    <div class="box-body">
                        <div class="col-xs-2">
                            <!-- leftNav -->
                            <div id="leftNav">
                                <div class="leftNav_con">
                                    @foreach($list as $v)
                                        <div class="tit" data-id="{{$v->id}}">
                                            <b></b>{{$v->pname}}
                                        </div>
                                        @if(!empty($v->allchild))
                                        <div class="con{{$v->id}}">
                                            @foreach($v->allchild as $c)
                                            <a href="#">{{$c->pname}}</a>
                                            @endforeach
                                        </div>
                                        @endif
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <div class="col-xs-9">asdfsdf </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection