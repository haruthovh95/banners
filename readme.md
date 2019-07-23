# Banners for Laravel
## Installation instructions
1) Install with Terminal
    ```
    composer require zakhayko/banners
    php artisan migrate
    php artisan vendor:publish --provider Zakhayko\Banners\ServiceProvider
    ```
2) Add route
    ```php
    Route::match(['get', 'post'], 'banners/{page}', 'BannersController@render');
    ```