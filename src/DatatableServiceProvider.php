<?php

namespace HamidRrj\LaravelDatatable;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DatatableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-datatable')
            ->hasConfigFile()
            ->publishesServiceProvider('DatatableServiceProvider')
            ->hasInstallCommand(function(InstallCommand $command){
                $command
                    ->copyAndRegisterServiceProviderInApp()
                    ->askToStarRepoOnGitHub('hamidrezarj/laravel-datatable');
            });
    }
}
