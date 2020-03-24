<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="nav-close"><i class="fa fa-times-circle"></i>
    </div>
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                                    <span class="block m-t-xs" style="font-size:20px;">
                                        <i class="fa fa-area-chart"></i>
                                        <strong class="font-bold">魔法数学</strong>
                                    </span>
                                </span>
                    </a>
                </div>
                <div class="logo-element">魔法数学
                </div>
            </li>

            <li>
                <a class="J_menuItem" href="{{\Illuminate\Support\Facades\URL::action("Admin\AdminController@index")}}">
                    <i class="fa fa-home"></i>
                    <span class="nav-label">主页</span>
                </a>
            </li>
            <?php
            $user = new \App\Services\AdminUser();
            $leftMenu  = $user->leftMenu();
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
        </ul>
    </div>
</nav>