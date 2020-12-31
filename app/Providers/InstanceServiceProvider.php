<?php

namespace App\Providers;

use App\Services\Admin\Asset\AssetService;
use App\Services\Admin\Asset\AssetServiceInterface;
use Illuminate\Support\ServiceProvider;

class InstanceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->bind(AssetServiceInterface::class, AssetService::class);
    }
}
