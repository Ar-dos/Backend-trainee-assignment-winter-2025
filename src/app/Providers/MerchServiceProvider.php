<?php

namespace App\Providers;

use App\Interfaces\MerchRepositoryInterface;
use App\Repositories\MerchRepository;
use Illuminate\Support\ServiceProvider;

class MerchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MerchRepositoryInterface::class,MerchRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
