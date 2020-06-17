<?php

return [
    'admin' => [
        'routeBaseName' => env('MY_APP_ADMIN_ROUTE_BASE_NAME', 'admin'),
        'baseUrl' => env('MY_APP_ADMIN_BASE_URL', 'admin'),
        'transBaseName' => env('MY_APP_ADMIN_TRANS_BASE_NAME', 'adminpanel'),
        'viewBasePath' => env('MY_APP_ADMIN_VIEW_BASE_PATH', 'pages.admin'),
        'layoutBasePath' => env('MY_APP_ADMIN_LAYOUT_BASE_PATH', 'layouts.admin'),
        'assetBasePath' => env('MY_APP_ADMIN_ASSET_BASE_PATH', '/adminpanel'),
    ],
    'public' => [
        'routeBaseName' => env('MY_APP_ADMIN_ROUTE_BASE_NAME', 'public'),
        'baseUrl' => env('MY_APP_ADMIN_BASE_URL', ''),
        'transBaseName' => env('MY_APP_ADMIN_TRANS_BASE_NAME', 'public'),
        'viewBasePath' => env('MY_APP_ADMIN_VIEW_BASE_PATH', 'pages.public'),
        'layoutBasePath' => env('MY_APP_ADMIN_LAYOUT_BASE_PATH', 'layouts.public'),
        'assetBasePath' => env('MY_APP_ADMIN_ASSET_BASE_PATH', '/public'),
    ],
];