<?php

namespace Zakhayko\Banners\Commands;

use Illuminate\Console\Command;
use Zakhayko\Banners\Models\Banner;

class BannersFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'banners:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes Unused Banners From Database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $classname = config('banners.controller_class');
        $settings = (new $classname)->getSettings();
        Banner::fixBanners($settings);
        $this->info('Unused banners removed successfully.');
    }
}
