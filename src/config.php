<?php
return [
    'table_name' => 'banners',
    'cache_prefix' => 'banners',
    'upload_dir' => 'uploads/banners/',
    'controller_class' => App\Http\Controllers\BannersController::class,
    'ignore_exceptions_if_empty' => false,
];
