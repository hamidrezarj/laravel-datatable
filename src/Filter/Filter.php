<?php

namespace HamidRrj\LaravelDatatable\Filter;

use HamidRrj\LaravelDatatable\Validators\FilterValidator;

class Filter
{
    private static FilterValidator $filterValidator;

    /**
     * @throws \HamidRrj\LaravelDatatable\Exceptions\InvalidFilterException
     */
    public function __construct(
        private string $id,
        private string|int|array $value,
        private string $fn,
        private string $datatype,
        private array $allowedFilters
    ) {
        self::$filterValidator = FilterValidator::getInstance();
        self::$filterValidator->isValid($this, $this->allowedFilters);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getValue(): array|int|string
    {
        return $this->value;
    }

    public function getFn(): string
    {
        return $this->fn;
    }

    public function getDatatype(): string
    {
        return $this->datatype;
    }

    public function getRelation(): string
    {
        $fieldArray = explode('.', $this->id);

        return count($fieldArray) > 1 ? $fieldArray[0] : '';
    }

    public function getColumn(): string
    {
        $fieldArray = explode('.', $this->id);

        return array_pop($fieldArray);
    }

    public function removeRelationFromId(): void
    {
        $this->id = $this->getColumn();
    }

    public function setValue(int|array|string $value): void
    {
        $this->value = $value;
    }
}
