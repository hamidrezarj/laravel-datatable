<?php

namespace HamidRrj\LaravelDatatable\Filter\SearchFunctions;

use Illuminate\Contracts\Database\Query\Builder;

class FilterBetween extends SearchFilter
{

    public function apply(): Builder
    {
        $query = $this->query;
        [$minVal, $maxVal] = $this->filter->getValue();

        if ($minVal) {
            $this->filter->setValue($minVal);
            $query = (new FilterGreaterThanOrEqual($this->query, $this->filter))->apply();
        }

        if ($maxVal) {
            $this->filter->setValue($maxVal);
            $query = (new FilterLessThanOrEqual($this->query, $this->filter))->apply();
        }

        return $query;
    }
}
