<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Player;
use App\Observers\PlayerObserver;
use Laravel\Passport\Passport;
// use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
  public function register(): void
  {
    //
  }

  public function boot(): void
  {
    Player::observe(PlayerObserver::class);

    Passport::enablePasswordGrant();

    // Inertia::share([
    //     'locale' => fn() => app()->getLocale(),

    //     'language' => fn() => translations(
    //         resource_path('lang/'. app()->getLocale() .'.json')
    //     ),
    // ]);
  }
}
