<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title')</title>
        <meta charset="utf-8">
        <style>
            html, body {
                height: 100%;
            }
            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Open Sans';
            }
            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }
            .content {
                text-align: center;
                display: inline-block;
            }
            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body class="THEME_TOP_ROW_BACKGROUND_COLOR THEME_MAIN_FONT_COLOR">
        <div class="container">
            <div class="content">
                <a class="" href="{{ url('') }}">
                    <img class="logo" alt="" src="<?=e(\App\Logic\App\Assets::logoUrl());?>">
                </a>
                <h3><?= request()->server('HTTP_HOST') ?></h3>
                <br>
                @yield('content')
            </div>
        </div>
    </body>
</html>