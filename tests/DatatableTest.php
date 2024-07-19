<?php

use \HamidRrj\LaravelDatatable\Tests\Models\User;
use \HamidRrj\LaravelDatatable\Facades\DatatableFacade;

it('can get data with empty filters', function () {

    $users = User::factory()->count(5)->create();

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => json_encode([]),
        'sorting' => json_encode([])
    ];

    $data = DatatableFacade::run(
        $query,
        $requestParameters
    );

    expect($data['data'])
        ->toEqual($users->toArray());

    expect($data['meta']['totalRowCount'])
        ->toBe(5);


});

it('can get correct data with providing start and size arguments', function () {

    $users = User::factory()->count(15)->create();

    $query = User::query();

    $requestParameters = [
        'start' => 5,
        'size' => 8,
        'filters' => json_encode([]),
        'sorting' => json_encode([])
    ];

    $data = DatatableFacade::run(
        $query,
        $requestParameters
    );

    $expected = array_slice($users->toArray(), 5, 8);

    expect($data['data'])
        ->toEqual($expected);

    expect($data['meta']['totalRowCount'])
        ->toBe(15);

});

it('throws exception when trying to filter a field which is not allowed to be filtered', function () {

    $query = User::query();

    $requestParameters = [
        'start' => 5,
        'size' => 8,
        'filters' => json_encode([
            [
                'id' => 'username',
                'value' => 'sth',
                'fn' => 'contains',
                'datatype' => 'text'
            ]
        ]),
        'sorting' => json_encode([])
    ];

    $data = DatatableFacade::run(
        $query,
        $requestParameters
    );

})->throws(\HamidRrj\LaravelDatatable\Exceptions\InvalidFilterException::class, "filtering field `username` is not allowed.");

it('throws exception when provided filter fn is not defined and is invalid', function () {

    $query = User::query();

    $requestParameters = [
        'start' => 5,
        'size' => 8,
        'filters' => json_encode([
            [
                'id' => 'username',
                'value' => 'sth',
                'fn' => 'sth',
                'datatype' => 'text'
            ]
        ]),
        'sorting' => json_encode([])
    ];

    $allowedFilters = array('username');

    $data = DatatableFacade::run(
        $query,
        $requestParameters,
        $allowedFilters
    );

})->throws(\HamidRrj\LaravelDatatable\Exceptions\InvalidFilterException::class, "search function `sth` is invalid.");

it('throws exception when provided filter datatype is not defined and is invalid', function () {

    $query = User::query();

    $requestParameters = [
        'start' => 5,
        'size' => 8,
        'filters' => json_encode([
            [
                'id' => 'username',
                'value' => 'sth',
                'fn' => 'notEquals',
                'datatype' => 'sth'
            ]
        ]),
        'sorting' => json_encode([])
    ];

    $allowedFilters = array('username');

    $data = DatatableFacade::run(
        $query,
        $requestParameters,
        $allowedFilters
    );

})->throws(\HamidRrj\LaravelDatatable\Exceptions\InvalidFilterException::class, "datatype `sth` is invalid.");

it('can get correct data with providing filter fn:`contains` and datatype:`text`', function () {

    $users = User::factory()->count(15)->create();

    $query = User::query();

    $requestParameters = [
        'start' => 5,
        'size' => 8,
        'filters' => json_encode([]),
        'sorting' => json_encode([])
    ];

    $data = DatatableFacade::run(
        $query,
        $requestParameters
    );

    $expected = array_slice($users->toArray(), 5, 8);

    expect($data['data'])
        ->toEqual($expected);

    expect($data['meta']['totalRowCount'])
        ->toBe(15);

});


// contains: text, numeric
// equals: text, numeric, date
// not equals: text, numeric, date
// between: numeric, date & text
// GT AND LT: numeric , date
