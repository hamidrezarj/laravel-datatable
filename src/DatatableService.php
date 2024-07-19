<?php

namespace HamidRrj\LaravelDatatable;

use HamidRrj\LaravelDatatable\Filter\ApplyFilter;
use HamidRrj\LaravelDatatable\Sort\ApplySort;
use Illuminate\Contracts\Database\Query\Builder;

class DatatableService
{
    protected array $allowedFilters;

    protected array $allowedRelations;

    protected array $allowedSortings;

    protected array $allowedSelects;

    private int $totalRowCount;

    public function __construct(
        protected Builder $query,
        private DatatableInput $dataTableInput
    ) {}

    public function setAllowedFilters(array $allowedFilters): DatatableService
    {
        $this->allowedFilters = $allowedFilters;

        return $this;
    }

    public function setAllowedRelations(array $allowedRelations): DatatableService
    {
        $this->allowedRelations = $allowedRelations;

        return $this;
    }

    public function setAllowedSortings(array $allowedSortings): DatatableService
    {
        $this->allowedSortings = $allowedSortings;

        return $this;
    }

    public function setAllowedSelects(array $allowedSelects): DatatableService
    {
        $this->allowedSelects = $allowedSelects;

        return $this;
    }

    /**
     * Handle 'getData' operations
     */
    public function getData(): array
    {
        $query = $this->buildQuery();
        $data = $query->get()->toArray();

        return [
            'data' => $data,
            'meta' => [
                'totalRowCount' => $this->totalRowCount,
            ],
        ];
    }

    protected function buildQuery(): Builder
    {
        $query = $this->query;

        foreach ($this->dataTableInput->getFilters() as $filter) {
            $query = (new ApplyFilter($query, $filter))->apply();
        }

        $query = $this->applySelect($query, $this->allowedSelects);
        $query = $this->includeRelationsInQuery($query, $this->allowedRelations);

        $this->totalRowCount = $query->count();

        $query->offset($this->dataTableInput->getStart());

        if (! is_null($this->dataTableInput->getSize())) {
            $query->limit($this->dataTableInput->getSize());
        }

        $sorting = $this->dataTableInput->getSorting();
        $query = (new ApplySort($query, $sorting))->apply();

        return $query;
    }

    protected function applySelect(Builder $query, array $selectedFields): Builder
    {
        if (! empty($selectedFields)) {
            $query->select($selectedFields);
        }

        return $query;
    }

    protected function includeRelationsInQuery(Builder $query, array $rels): Builder
    {
        if (! empty($rels)) {
            $query->with($rels);
        }

        return $query;
    }

    // (later) define mapping of relation names to prevent relation name expose.
    // (later) define mapping of column names to prevent column name expose.
}
