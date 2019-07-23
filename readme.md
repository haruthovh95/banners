# Banners for Laravel
## Installation instructions
1) Install with Terminal
    ```
    composer require zakhayko/banners
    php artisan vendor:publish --provider Zakhayko\Banners\ServiceProvider
    php artisan migrate
    ```
2) Add route
    ```php
    Route::match(['get', 'post'], 'banners/{page}', 'BannersController@render');
    ```
