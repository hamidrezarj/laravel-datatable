<?php

namespace HamidRrj\LaravelDatatable\Filter;

use HamidRrj\LaravelDatatable\Enums\SearchType;
use HamidRrj\LaravelDatatable\Exceptions\InvalidFilterException;
use HamidRrj\LaravelDatatable\Filter\SearchFunctions\FilterBetween;
use HamidRrj\LaravelDatatable\Filter\SearchFunctions\FilterContains;
use HamidRrj\LaravelDatatable\Filter\SearchFunctions\FilterEquals;
use HamidRrj\LaravelDatatable\Filter\SearchFunctions\FilterGreaterThan;
use HamidRrj\LaravelDatatable\Filter\SearchFunctions\FilterLessThan;
use HamidRrj\LaravelDatatable\Filter\SearchFunctions\FilterNotEquals;
use HamidRrj\LaravelDatatable\Filter\SearchFunctions\SearchFilter;
use Illuminate\Contracts\Database\Query\Builder;

class ApplyFilter
{
    private SearchFilter $searchFilter;

    public function __construct(
        private Builder $query,
        private Filter $filter,
    ) {
    }

    public function apply(): Builder
    {
        $filter = $this->filter;
        $query = $this->query;

        $searchType = SearchType::from($filter->getFn());
        switch ($searchType) {
            case SearchType::CONTAINS:
                $this->searchFilter = new FilterContains($query, $filter);
                break;

            case SearchType::EQUALS:
                $this->searchFilter = new FilterEquals($query, $filter);
                break;

            case SearchType::NOT_EQUALS:
                $this->searchFilter = new FilterNotEquals($query, $filter);
                break;

            case SearchType::BETWEEN:
                $this->searchFilter = new FilterBetween($query, $filter);
                break;

            case SearchType::GREATER_THAN:
                $this->searchFilter = new FilterGreaterThan($query, $filter);
                break;

            case SearchType::LESS_THAN:
                $this->searchFilter = new FilterLessThan($query, $filter);
                break;

            default:
                $searchFunction = $filter->getFn();
                throw new InvalidFilterException($searchFunction, "search function `$searchFunction` is invalid.");
        }

        $relation = $this->filter->getRelation();

        return $relation ? $this->applyFilterToRelation($relation) : $this->searchFilter->apply();
    }

    protected function applyFilterToRelation(string $relation): Builder
    {
        return $this->query->whereHas($relation, function (Builder $query) {
            $this->filter->removeRelationFromId();
            $this->applyFilter($query, $this->filter);
        });
    }

    private function applyFilter(Builder $query, Filter $filter): Builder
    {
        return (new ApplyFilter($query, $filter))->apply();
    }
}
