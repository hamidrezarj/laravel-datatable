<?php

namespace HamidRrj\LaravelDatatable\Filter\SearchFunctions;

use HamidRrj\LaravelDatatable\Filter\Filter;
use Illuminate\Contracts\Database\Query\Builder;

abstract class SearchFilter
{
    /**
     * @param Builder $query
     * @param Filter $filter
     */
    public function __construct(
        protected Builder $query,
        protected Filter  $filter,
    )
    {
    }

    abstract public function apply(): Builder;
}
