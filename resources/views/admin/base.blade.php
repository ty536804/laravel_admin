<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{config('app.name')}} | @yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <![endif]-->
    @include("admin.css")
    @yield('css')
</head>

<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<div id="wrapper">
    <!--左侧导航开始-->
    @include("admin.left")
    <!--左侧导航结束-->
    <!--右侧部分开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">
        @include("admin.head")
{{--        <div class="row J_mainContent" id="content-main">--}}
{{--            <iframe id="J_iframe" width="100%" height="100%" src="index_v1.html?v=4.0" frameborder="0" data-id="index_v1.html" seamless>--}}

{{--            </iframe>--}}
{{--        </div>--}}
        @yield('content')
    </div>
    <!--右侧部分结束-->
</div>
@include("admin.js")
@yield('js')
</body>

</html>
