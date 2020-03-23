<?php
return array(
    'assets' => env('WWW_ASSETS',env('APP_URL')),
    'uploadfile' => env('WWW_UPLOADFILE', base_path('public/storage/uploadfile/')),
    'imgurl' => env('WWW_IMG', ''),
    'name' => env('WWW_NAME', '魔法数学'),
    'nick' => env('WWW_NICK', 'majic'),
    'company' => env('WWW_COMPANY', '易学教育科技有限公司'),
);