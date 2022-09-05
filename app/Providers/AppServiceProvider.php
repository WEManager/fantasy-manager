<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Player;
use App\Observers\PlayerObserver;
use Illuminate\Support\Facades\URL;

// use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider {
    public function register(): void {
        //
    }

    public function boot(): void {
        // URL::forceScheme('https');

        Player::observe(PlayerObserver::class);

        // Inertia::share([
        //     'locale' => fn() => app()->getLocale(),

        //     'language' => fn() => translations(
        //         resource_path('lang/'. app()->getLocale() .'.json')
        //     ),
        // ]);
    }
}
