<?php

namespace HamidRrj\LaravelDatatable\Enums;

enum DataType: string
{
    case NUMERIC = 'numeric';
    case TEXT = 'text';
    case DATE = 'date';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
