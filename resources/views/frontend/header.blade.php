<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="title m-b-md">
            {{ config('app.name', '易学教育') }}
        </div>
        <div class="links">
            @foreach($menuList as $menu)
                <a href="{{$menu->base_url}}">{{$menu->position_name}}</a>
            @endforeach
        </div>
    </div>
</div>
