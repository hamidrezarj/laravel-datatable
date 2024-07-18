<?php

namespace HamidRrj\LaravelDatatable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \HamidRrj\LaravelDatatable\DatatableFacade
 */
class DatatableFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \HamidRrj\LaravelDatatable\Facades\Datatable::class;
    }
}
