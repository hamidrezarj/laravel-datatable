<?php

namespace HamidRrj\LaravelDatatable\Filter\SearchFunctions;

use HamidRrj\LaravelDatatable\Enums\DataType;
use Illuminate\Contracts\Database\Query\Builder;

class FilterEquals extends SearchFilter
{

    public function apply(): Builder
    {
        $column = $this->filter->getId();
        $value = $this->filter->getValue();

        if ($this->filter->getDatatype() == DataType::TEXT->value) {
            $query = $this->searchIgnoreCase($column, $value);
        } else {
            $query = $this->query->where($column, $value);
        }

        return $query;
    }

    private function searchIgnoreCase(string $column, string $value): Builder
    {
        $value = strtolower($value);
        return $this->query->whereRaw("LOWER($column) = ?", [$value]);
    }
}
