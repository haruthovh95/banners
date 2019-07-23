<?php

namespace Zakhayko\Banners\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BannersSync extends Command
{

    private $exampleFile = 'app/Services/Banners/src/example.blade.php';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'banners:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $dir = resource_path('views/vendor/banners/');
        $pages_dir = $dir.'pages/';
        $empty_file = $dir.'components/empty_page.blade.php';
        if (!file_exists($dir.'pages') || !file_exists($empty_file)) {
            $this->error('Run "php artisan vendor:publish --provider Zakhayko\Banners\ServiceProvider" first.');
            return;
        };
        $classname = config('banners.controller_class');
        $settings = (new $classname)->getSettings();
        $keys = array_keys($settings);
        $created_files = false;
        foreach($keys as $key) {
            $filename = $key.'.blade.php';
            $file_path = $pages_dir.$filename;
            if (!file_exists($file_path)) {
                $this->info('Created file "'.$filename.'".');
                File::copy($empty_file, $file_path);
                $created_files = true;
            }
        }
        if (!$created_files) $this->info('Nothing to create.');
        return;
    }
}
