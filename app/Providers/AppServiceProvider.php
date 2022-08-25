<?php

namespace App\Providers;

use App\Models\Person;
use App\Observers\PersonObserver;
use Illuminate\Support\ServiceProvider;
// use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider {
    public function register(): void {
        //
    }

    public function boot(): void {
        Person::observe(PersonObserver::class);

        // Inertia::share([
        //     'locale' => fn() => app()->getLocale(),

        //     'language' => fn() => translations(
        //         resource_path('lang/'. app()->getLocale() .'.json')
        //     ),
        // ]);
    }
}
