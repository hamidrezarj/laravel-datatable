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
     * @param Model|Builder $mixed
     * @param array $requestParameters
     * @param array $allowedFilters
     * @param array $allowedRelations
     * @param array $allowedSortings
     * @param array $allowedSelects
     * @return array
     * @throws InvalidParameterInterface if input parameters are invalid.
     */
    public function run(
        Model|Builder $mixed,
        array         $requestParameters,
        array         $allowedFilters = [],
        array         $allowedSortings = [],
        array         $allowedSelects = [],
        array         $allowedRelations = [],
    ): array
    {

//        $filters = json_decode($requestParameters['filters']);
//        $sorting = json_decode($requestParameters['sorting']);
        $rels = array_key_exists('rels', $requestParameters) ? $requestParameters['rels'] : array();

        $dataTableInput = new DataTableInput(
            $requestParameters['start'],
            $requestParameters['size'],
            $requestParameters['filters'],
            $requestParameters['sorting'],
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
