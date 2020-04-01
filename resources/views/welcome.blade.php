<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', '易学教育') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    {{ config('app.name', '易学教育') }}
                </div>

                <div class="links">
                    <a href="/">首页</a>
                    <a href="https://laracasts.com">关于魔数</a>
                    <a href="https://laravel-news.com">课程体系</a>
                    <a href="https://blog.laravel.com">教研教学</a>
                    <a href="https://nova.laravel.com">AI学习平台</a>
                    <a href="https://forge.laravel.com">OMO模式</a>
                    <a href="https://vapor.laravel.com">全国校区</a>
                    <a href="https://github.com/laravel/laravel">加盟授权</a>
                    <a href="https://github.com/laravel/laravel">APP下载</a>
                </div>
            </div>
        </div>
    </body>
</html>
