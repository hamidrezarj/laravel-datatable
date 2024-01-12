<?php

namespace HamidRrj\LaravelDatatable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \HamidRrj\LaravelDatatable\LaravelDatatable
 */
class LaravelDatatable extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \HamidRrj\LaravelDatatable\LaravelDatatable::class;
    }
}
