<?php

namespace EJLab\Laravel\MultiTenant\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use EJLab\Laravel\MultiTenant\Commands\Migrate\MigrateCommand;
use EJLab\Laravel\MultiTenant\Commands\Migrate\MigrateInstallCommand;
use EJLab\Laravel\MultiTenant\Commands\Migrate\MigrateMakeCommand;

class MultiTenantServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/elmt.php' => config_path('elmt.php'),
            __DIR__.'/../../database/migrations/' => database_path('migrations'),
            __DIR__.'/../../database/Tenant.php' => app_path('Tenant.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            // MigrateCommand::class,
        ]);

        $this->app->extend('command.migrate', function ($object, $app) {
            return new MigrateCommand($app['migrator']);
        });

        $this->app->extend('command.migrate.install', function ($object, $app) {
            return new MigrateInstallCommand($app['migration.repository']);
        });
        
        $this->app->extend('command.migrate.make', function ($object, $app) {
            return new MigrateMakeCommand($app['migration.creator'], $app['composer']);
        });
    }
}
