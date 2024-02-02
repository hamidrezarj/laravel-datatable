<?php

namespace HamidRrj\LaravelDatatable\Sort;

use Illuminate\Contracts\Database\Query\Builder;

class ApplySort
{

    /**
     * @param Builder $query
     * @param ?Sort $sort
     */
    public function __construct(
        private Builder $query,
        private ?Sort    $sort,
    )
    {
    }

    public function apply(): Builder
    {
        $query = $this->query;

        if (!is_null($this->sort)) {
            $query->orderBy($this->sort->getId(), $this->sort->getDirection());
        }

        return $query;
    }
}
