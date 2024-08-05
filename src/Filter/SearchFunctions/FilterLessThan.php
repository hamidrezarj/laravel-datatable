<?php

namespace HamidRrj\LaravelDatatable\Filter\SearchFunctions;

use HamidRrj\LaravelDatatable\Enums\DataType;
use Illuminate\Contracts\Database\Query\Builder;

class FilterLessThan extends SearchFilter
{
    public function apply(): Builder
    {
        $value = ($this->filter->getDatatype() == DataType::NUMERIC->value) ?
            (float) $this->filter->getValue() : $this->filter->getValue();

        return $this->query->where($this->filter->getId(), '<', $value);
    }
}
