<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Player;
use App\Observers\PlayerObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
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

    $this->configureCommands();
    $this->configureModels();
    $this->configureUrl();
    $this->configureVite();
  }

  /**
   * Configure the application's commands.
   */
  private function configureCommands()
  {
    DB::prohibitDestructiveCommands($this->app->isProduction(),);
  }

  /**
   * Configure the application's models.
   */
  private function configureModels(): void
  {
    Model::shouldBeStrict();

    Model::unguard();
  }

  /**
   * Configure the application's URL.
   */
  private function configureUrl(): void
  {
    URL::forceScheme('https');
  }

  /**
   * Configure the application's Vite.
   */
  private function configureVite(): void
  {
    Vite::usePrefetchStrategy('aggressive');
  }
}
