<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Seat;
use App\Observers\SeatObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Seat::observe(SeatObserver::class);
    }
}
