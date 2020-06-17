<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Logic\App\AppManager;

class ConfigServiceProvider extends ServiceProvider {
    
    public function boot()
    {
        AppManager::setupMyApp();
    }
}