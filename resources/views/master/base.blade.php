<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{config('app.name')}} | @yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    @include('master.css')
    @yield('css')
    <style type="text/css">
        @media screen and (max-width:800px) {
            table {
                border: 0 !important;
            }
            table thead {
                display: none;
            }
            table tr {
                margin-bottom: 10px;
                display: block;
                border-bottom: 1px solid #c6c6c6;
            }
            table td {
                display: block;
                text-align: right;
                min-height: 40px;
            }
            table td:first-child {
                background-color: #428bca;
                background-image: linear-gradient(to bottom, #5A95CA, #357ebd);
                background-repeat: repeat-x;
                border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
                color: #fff;
                text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
            }
            table td:before {
                content: attr(data-label);
                float: left;
                text-transform: uppercase;
                font-weight: bold;
            }
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini fixed">
<div class="wrapper">
    @include('master.header');
    @include('master.left');
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @yield('menuname')
                <small>@yield('smallname')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{\Illuminate\Support\Facades\URL::action('Admin\AdminController@main')}}"><i class="fa fa-dashboard"></i> 主页</a></li>
                <li class="active">@yield('smallname')</li>
            </ol>
        </section>
        <!-- Main content -->
        @yield('content')
        <!-- /.content -->
    </div><!-- /.content-wrapper -->
    @include('master.footer')
    @include('master.control')
    <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->

@include('master.js')
@yield('js')
</body>
</html>
