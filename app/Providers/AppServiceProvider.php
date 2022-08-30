<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Player;
use App\Observers\PlayerObserver;
// use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider {
    public function register(): void {
        //
    }

    public function boot(): void {
        Player::observe(PlayerObserver::class);

        // Inertia::share([
        //     'locale' => fn() => app()->getLocale(),

        //     'language' => fn() => translations(
        //         resource_path('lang/'. app()->getLocale() .'.json')
        //     ),
        // ]);
    }
}
