<?php

namespace HamidRrj\LaravelDatatable\Validators;

use HamidRrj\LaravelDatatable\Exceptions\InvalidRelationException;

class RelationValidator
{
    public static function validate(?array $rels, array $allowedRelations): bool
    {
        foreach ($rels as $relation) {

            if (!self::isAllowed($relation, $allowedRelations)) {
                throw new InvalidRelationException($relation, "relation `$relation` is not allowed.");
            }
        }

        return true;
    }

    public static function isAllowed(string $relation, array $allowedRelations): bool
    {
        return !$relation || in_array($relation, $allowedRelations);
    }
}
