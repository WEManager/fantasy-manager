<?php

namespace App\Providers;

use App\Models\Club;
use App\Models\Lineup;
use App\Policies\ClubPolicy;
use App\Policies\LineupPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
  /**
   * The model to policy mappings for the application.
   *
   * @var array<class-string, class-string>
   */
  protected $policies = [
    Club::class => ClubPolicy::class,
    Lineup::class => LineupPolicy::class,
  ];

  /**
   * Register any authentication / authorization services.
   *
   * @return void
   */
  public function boot() {}
}
