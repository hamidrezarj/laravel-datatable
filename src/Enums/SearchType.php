<?php

namespace HamidRrj\LaravelDatatable\Enums;

enum SearchType: string
{
    case CONTAINS = 'contains';
    case EQUALS = 'equals';
    case NOT_EQUALS = 'notEquals';
    case BETWEEN = 'between';
    case GREATER_THAN = 'greaterThan';
    case LESS_THAN = 'lessThan';
    case FUZZY = 'fuzzy';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
