<?php

namespace Creacoon\AppVersioning;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Creacoon\AppVersioning\Commands\VersionManagerCommand;

class VersionManagerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('version-manager')
            ->hasConfigFile('version-manager')
            ->hasCommand(VersionManagerCommand::class);
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        parent::register();

        $this->app->singleton('version-manager', fn () => new VersionManager());
    }
}
