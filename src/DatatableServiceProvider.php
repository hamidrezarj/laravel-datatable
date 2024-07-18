<?php

namespace HamidRrj\LaravelDatatable;

use HamidRrj\LaravelDatatable\Commands\LaravelDatatableCommand;
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
            ->hasConfigFile();
    }
}
