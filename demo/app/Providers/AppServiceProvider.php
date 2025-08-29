<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use LaraZeus\Mark\Facades\Mark;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Mark::markerModel(User::class);
    }
}
