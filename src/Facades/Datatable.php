<?php

namespace HamidRrj\LaravelDatatable\Facades;

use HamidRrj\LaravelDatatable\DatatableInput;
use HamidRrj\LaravelDatatable\DatatableService;
use HamidRrj\LaravelDatatable\Exceptions\InvalidParameterInterface;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Datatable
{
    /**
     * Extracts data from request, passes to datatable service and prepares data for response.
     *
     * @throws InvalidParameterInterface if input parameters are invalid.
     */
    public function run(
        Model|Builder $mixed,
        array $requestParameters,
        array $allowedFilters = [],
        array $allowedRelations = [],
        array $allowedSortings = [],
        array $allowedSelects = []
    ): array {

        $filters = json_decode($requestParameters['filters']);
        $sorting = json_decode($requestParameters['sorting']);
        $rels = array_key_exists('rels', $requestParameters) ? $requestParameters['rels'] : [];

        $dataTableInput = new DataTableInput(
            $requestParameters['start'],
            $requestParameters['size'],
            $filters,
            $sorting,
            $rels,
            $allowedFilters,
            $allowedSortings
        );

        $query = $this->makeQueryFromModel($mixed);

        $dataTableService = (new DataTableService($query, $dataTableInput))
            ->setAllowedFilters($allowedFilters)
            ->setAllowedRelations($allowedRelations)
            ->setAllowedSortings($allowedSortings)
            ->setAllowedSelects($allowedSelects);

        return $dataTableService->getData();
    }

    protected function makeQueryFromModel(Model|Builder $mixed): Builder
    {
        return ($mixed instanceof Model) ? $mixed->query() : $mixed;
    }
}
