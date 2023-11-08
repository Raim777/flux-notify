<?php

namespace Raim\FluxNotify;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class FluxNotificationsServiceProvider extends ServiceProvider
{

    public function boot()
    {
        // Catalog::observe(new CatalogObserver());

        // if ($this->app->runningInConsole()) {
        //     $this->publishes([
        //         __DIR__ . '/../config/flux-catalog.php' => config_path('flux-catalog.php'),
        //     ], 'flux-catalog-config');

        //     $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        //     $this->publishMigrations();
        // }

        // $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }

    public function register()
    {
        // $this->app->bind('textConverter', TextConverterHelper::class);
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     */
    protected function getMigrationFileName($index,string $migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make([$this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR])
            ->flatMap(fn($path) => $filesystem->glob($path . '*_' . $migrationFileName))
            ->push($this->app->databasePath() . "/migrations/{$timestamp}{$index}_{$migrationFileName}")
            ->first();
    }
}
