<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Player;
use App\Observers\PlayerObserver;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Sleep;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Player::observe(PlayerObserver::class);

        $this->configureCommands();
        $this->configureModels();
        $this->configureUrl();
        $this->configureVite();
        $this->configureDate();
        $this->configureSleep();
    }

    /**
     * Configure the application's commands.
     */
    private function configureCommands(): void
    {
        // Blocks potentially destructive Artisan commands in production (e.g., migrate:fresh).
        DB::prohibitDestructiveCommands(App::isProduction());
    }

    /**
     * Configure the application's models.
     */
    private function configureModels(): void
    {
        Model::shouldBeStrict();

        // Disables Laravel's mass assignment protection globally (opt-in).
        Model::unguard();

        // Automatically eager loads relationships defined in the model's $with property.
        Model::automaticallyEagerLoadRelationships();
    }

    /**
     * Configure the application's URL.
     */
    private function configureUrl(): void
    {
        // Forces all generated URLs to use https://.
        URL::forceScheme('https');
    }

    /**
     * Configure the application's Vite.
     */
    private function configureVite(): void
    {
        // Configures Laravel Vite to preload assets more aggressively.
        Vite::usePrefetchStrategy('aggressive');

        // Preloads assets in parallel with a concurrency limit.
        Vite::prefetch(concurrency: 3);
    }

    /**
     * Configure the application's Date.
     */
    private function configureDate(): void
    {
        // Uses CarbonImmutable instead of mutable date objects across your app.
        Date::use(CarbonImmutable::class);
    }

    /**
     * Configure the application's Sleep.
     */
    private function configureSleep(): void
    {
        // Configures Laravel Sleep Facade to be faked.
        // Why: Avoid unexpected sleep during testing cases.
        Sleep::fake();
    }
}
