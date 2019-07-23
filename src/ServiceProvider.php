<?php

namespace Zakhayko\Banners;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Zakhayko\Banners\Commands\BannersFix;
use Zakhayko\Banners\Commands\BannersSync;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config.php',
            'banners'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'banners');
        $this->commands([
            BannersFix::class,
            BannersSync::class,
        ]);
        $this->publishes([
            __DIR__.'/config.php' => config_path('banners.php'),
            __DIR__.'/views' => resource_path('views/vendor/banners'),
            __DIR__.'/controller' => app_path('Http/Controllers/BannersController.php'),
        ]);
        Blade::directive('banner', function ($expression) {
            return "<?php echo banner(\$params, \$banners, \$key??null, \$i??0, $expression) ?>";
        });
        Blade::directive('cards', function ($expression) {
            return "<?php \$thisExpression=$expression; \$key=\$thisExpression['banners']; \$thisExpression['count']=\$params[\$key]['count']??1; \$__env->startComponent('banners::components.cards', \$thisExpression); for(\$i=0; \$i<\$thisExpression['count']; \$i++): \$__env->slot('tab_'.\$i)?>";
        });
        Blade::directive('endcards', function($expression){
            return "<?php \$__env->endSlot(); endfor; unset(\$i, \$key, \$thisExpression); echo \$__env->renderComponent()?>";
        });
        Blade::directive('banners', function ($expression) {
            return "<?php \$this_count=\$params[$expression]['count']??1; \$key=$expression; for(\$i=0; \$i<\$this_count; \$i++): ?>";
        });
        Blade::directive('endbanners', function () {
            return "<?php endfor; unset(\$i, \$key, \$this_count); ?>";
        });
    }
}
