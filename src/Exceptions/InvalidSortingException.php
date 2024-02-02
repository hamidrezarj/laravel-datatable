<?php

namespace HamidRrj\LaravelDatatable\Exceptions;

use Exception;

class InvalidSortingException extends Exception implements InvalidParameterInterface
{
    protected $fieldName;

    public function __construct($fieldName, $message = "", $code = 400, \Throwable $previous = null)
    {
        $this->fieldName = $fieldName;
        parent::__construct($message, $code, $previous);
    }

    public function getFieldName()
    {
        return $this->fieldName;
    }
}
