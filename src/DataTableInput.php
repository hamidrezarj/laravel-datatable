<?php

namespace HamidRrj\LaravelDatatable;

use HamidRrj\LaravelDatatable\Filter\Filter;
use HamidRrj\LaravelDatatable\Sort\Sort;

class DataTableInput
{
    /**
     * @param int $start
     * @param int $size
     * @param array $filters
     * @param array $sorting
     * @param array $rels
     */
    public function __construct(
        private int   $start,
        private ?int   $size,
        private array $filters,
        private array $sorting,
        private array $rels,
        private array $allowedFilters,
        private array $allowedSortings,
    )
    {
    }

    public function getStart(): int
    {
        return $this->start;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    /**
     * @return array returns an array of Filter objects
     */
    public function getFilters(): array
    {
        $filters = array();

        foreach ($this->filters as $filter) {
            $filters[] = new Filter(
                $filter->id,
                $filter->value,
                $filter->fn,
                $filter->datatype,
                $this->allowedFilters
            );
        }

        return $filters;
    }

    /**
     * @return Sort|null
     */
    public function getSorting(): ?Sort
    {
        return !empty($this->sorting) ?
            new Sort($this->sorting[0]->id, $this->sorting[0]->desc, $this->allowedSortings) : null;
    }

    public function getRelations(): array
    {
        return $this->rels;
    }


}
