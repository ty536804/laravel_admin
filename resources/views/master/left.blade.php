<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?php
                 $user = new \App\Services\AdminUser();
                 if(empty($userinfo)){
                     $userinfo = $user->getUser();
                 }
                ?>
                @if(!empty($userinfo->m_url))
                        <img src="{!! uploadfile() !!}{{ $userinfo->m_url }}" class="img-circle" alt="User Image">
                @else
                        <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="img-circle"  alt="User Image">
                @endif

            </div>
            <div class="pull-left info">
                @if(empty($userinfo->position->position_name) && empty($userinfo->nick_name))
                    <p>Alexander Pierce</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                @else
                    <p>{{$userinfo->nick_name ?? "张三"}}</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> {{$userinfo->position->position_name}}</a>
                @endif
            </div>
        </div>
        <!-- search form -->
        <form action="" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..." id="pname">
                <span class="input-group-btn">
                <button type="button" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <div id="search_list">
        </div>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">系统导航</li>
            <?php
            $leftMenu  = $user->leftMenu();
            if(empty($leftMenu)){
                $url = env('APP_URL');
                header("Location: $url");
                exit ;
            }
            $mainMenu = $leftMenu[0];
            ?>
            @foreach($mainMenu as $menu)
                @if($menu['purl']!='#')
                    <li class="{{$menu['active']}}">
                        <a href="{{URL::action($menu['purl'])}}" class="hvr-wobble-skew">
                            <i class="fa {{$menu['icon']}}"></i> <span>{{$menu['pname']}}</span>
                            {{--<small class="label pull-right bg-green">new</small>--}}
                        </a>
                    </li>
                @else
                    {{--<li class="active treeview"> 当前菜单加 Active--}}
                    <li class="{{$menu['active']}}  treeview">
                        <a href="#" class="hvr-wobble-skew">
                            <i class="fa {{$menu['icon']}}"></i>
                            <span>{{$menu['pname']}}</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>

                        <ul class="treeview-menu">
                            @if(!empty($leftMenu[$menu['id']]))
                            @foreach($leftMenu[$menu['id']] as $subMenu)
                                <li class="{{$subMenu['active']}}"><a href="{{URL::action($subMenu['purl'])}}"><i class="fa fa-telegram"></i> {{$subMenu['pname']}}</a></li>
                            @endforeach
                            @endif
                        </ul>
                    </li>
                @endif
            @endforeach

            {{--<li class="header">标签</li>--}}
            {{--<li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>重要的</span></a></li>--}}
            {{--<li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>警告</span></a></li>--}}
            {{--<li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>注意</span></a></li>--}}
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
<script>
    $("#search-btn").click(function () {
        var pname = $("#pname").val();
        if (pname != "") {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "{{URL::action('Admin\PowerController@searchUrl')}}",
                data: {'_token': '{{ csrf_token() }}', 'pname': pname},
                success: function (result) {
                    if (result.code == "10000") {
                        $("#search_list").html(result.data);

                    } else {
                        sweetAlert("操作失败", result.msg, 'error');
                    }

                },
                error: function (result) {
                    swal({title: "网络错误", type: 'error'});
                }
            });
        }


    })
</script>
