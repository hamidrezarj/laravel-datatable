<?php

namespace HamidRrj\LaravelDatatable\Validators;

use HamidRrj\LaravelDatatable\Enums\SearchType;
use HamidRrj\LaravelDatatable\Enums\DataType;
use HamidRrj\LaravelDatatable\Exceptions\InvalidFilterException;
use HamidRrj\LaravelDatatable\Filter\Filter;

class FilterValidator
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance(): FilterValidator
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function isValid(Filter $filter, array $allowedFilters): bool
    {
        if (!$this->isAllowed($filter, $allowedFilters)) {
            $filterId = $filter->getId();
            throw new InvalidFilterException($filter->getId(), "filtering field `$filterId` is not allowed.");
        }

        if (!$this->isValidSearchFunction($filter)) {
            $searchFunction = $filter->getFn();
            throw new InvalidFilterException($searchFunction, "search function `$searchFunction` is invalid.");
        }

        if ($this->isValidDataType($filter) == -1) {
            throw new InvalidFilterException(null, "datatype property is not set in `filters` array.");
        }

        if (!$this->isValidDataType($filter)) {
            $datatype = $filter->getDatatype();
            throw new InvalidFilterException($datatype, "datatype `$datatype` is invalid.");
        }

        return true;
    }

    protected function isAllowed(Filter $filter, array $allowedFilters): bool
    {
        return in_array($filter->getId(), $allowedFilters);
    }

    protected function isValidSearchFunction(Filter $filter): bool
    {
        $searchFunction = $filter->getFn();
        return isset($searchFunction) && in_array($searchFunction, SearchType::values());
    }

    protected function isValidDataType(Filter $filter): int
    {
        if (!property_exists($filter, 'datatype'))
            return -1;

        return $filter->getDatatype() && in_array($filter->getDatatype(), DataType::values()) ? 1 : 0;
    }
}
