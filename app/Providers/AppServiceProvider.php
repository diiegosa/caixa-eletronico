<?php

namespace App\Providers;

use App\Models\Withdraw;
use App\Repositories\AtmRepository;
use App\Repositories\Interfaces\AtmRepositoryInterface;
use App\Repositories\Interfaces\WithdrawRepositoryInterface;
use App\Repositories\WithdrawRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AtmRepositoryInterface::class, AtmRepository::class);
        $this->app->bind(WithdrawRepositoryInterface::class, WithdrawRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
