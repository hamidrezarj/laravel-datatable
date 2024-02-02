<?php

namespace HamidRrj\LaravelDatatable\Sort;

use HamidRrj\LaravelDatatable\Validators\SortingValidator;

class Sort
{
    private static SortingValidator $sortingValidator;

    /**
     * @param string $id
     * @param bool $desc
     * @param array $allowedSortings
     * @throws \HamidRrj\LaravelDatatable\Exceptions\InvalidSortingException
     */
    public function __construct(
        private string $id,
        private bool   $desc,
        private array $allowedSortings,
    )
    {
        self::$sortingValidator = SortingValidator::getInstance();
        self::$sortingValidator->isValid($this, $this->allowedSortings);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getDirection(): string
    {
        return $this->desc === true ? 'desc' : 'asc';
    }
}
