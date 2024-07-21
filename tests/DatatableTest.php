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

    $expectedUsers = User::factory()
        ->count(4)
        ->create([
            'name' => 'First User'
        ]);

    User::factory()
        ->count(6)
        ->create([
            'name' => 'Non match user'
        ]);

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => json_encode([
            [
                'id' => 'name',
                'value' => 'first',
                'fn' => 'contains',
                'datatype' => 'text'
            ]
        ]),
        'sorting' => json_encode([])
    ];

    $allowedFilters = array('name');

    $data = DatatableFacade::run(
        $query,
        $requestParameters,
        $allowedFilters
    );

    $expected = $expectedUsers->toArray();

    expect($data['data'])
        ->toEqual($expected);

    expect($data['meta']['totalRowCount'])
        ->toBe(4);

});

it('can get correct data with providing filter fn:`contains` and datatype:`numeric`', function () {

    $expectedUsers = User::factory()
        ->count(5)
        ->state(new \Illuminate\Database\Eloquent\Factories\Sequence(
            ['age' => 3],
            ['age' => 13],
            ['age' => 53],
        ))
        ->create();

    User::factory()
        ->count(6)
        ->state(new \Illuminate\Database\Eloquent\Factories\Sequence(
            ['age' => 12],
            ['age' => 98],
            ['age' => 42],
        ))
        ->create();

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => json_encode([
            [
                'id' => 'age',
                'value' => '3',
                'fn' => 'contains',
                'datatype' => 'numeric'
            ]
        ]),
        'sorting' => json_encode([])
    ];

    $allowedFilters = array('age');

    $data = DatatableFacade::run(
        $query,
        $requestParameters,
        $allowedFilters
    );

    $expected = $expectedUsers->toArray();

    expect($data['data'])
        ->toEqual($expected);

    expect($data['meta']['totalRowCount'])
        ->toBe(5);

});

it('can get correct data with providing filter fn:`equals` and datatype:`text`', function () {

    $expectedUsers = User::factory()
        ->count(4)
        ->create([
            'name' => 'First User'
        ]);

    User::factory()
        ->count(6)
        ->create([
            'name' => 'Non match user'
        ]);

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => json_encode([
            [
                'id' => 'name',
                'value' => 'first user',
                'fn' => 'equals',
                'datatype' => 'text'
            ]
        ]),
        'sorting' => json_encode([])
    ];

    $allowedFilters = array('name');

    $data = DatatableFacade::run(
        $query,
        $requestParameters,
        $allowedFilters
    );

    $expected = $expectedUsers->toArray();

    expect($data['data'])
        ->toEqual($expected);

    expect($data['meta']['totalRowCount'])
        ->toBe(4);

});

/* FN: equals */
it('can get correct data with providing filter fn:`equals` and datatype:`numeric`', function () {

    $expectedUsers = User::factory()
        ->count(3)
        ->create([
            'age' => 13
        ]);

    User::factory()
        ->count(6)
        ->create([
            'age' => 42
        ]);

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => json_encode([
            [
                'id' => 'age',
                'value' => '13',
                'fn' => 'equals',
                'datatype' => 'numeric'
            ]
        ]),
        'sorting' => json_encode([])
    ];

    $allowedFilters = array('age');

    $data = DatatableFacade::run(
        $query,
        $requestParameters,
        $allowedFilters
    );

    $expected = $expectedUsers->toArray();

    expect($data['data'])
        ->toEqual($expected);

    expect($data['meta']['totalRowCount'])
        ->toBe(3);

});

it('can get correct data with providing filter fn:`equals` and datatype:`date`', function () {

    $expectedUsers = User::factory()
        ->count(5)
        ->create([
            'created_at' => '2024-05-23 12:00:00'
        ]);


    User::factory()
        ->count(6)
        ->create();

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => json_encode([
            [
                'id' => 'created_at',
                'value' => '2024-05-23 12:00:00',
                'fn' => 'equals',
                'datatype' => 'date'
            ]
        ]),
        'sorting' => json_encode([])
    ];

    $allowedFilters = array('created_at');

    $data = DatatableFacade::run(
        $query,
        $requestParameters,
        $allowedFilters
    );

    $expected = $expectedUsers->toArray();

    expect($data['data'])
        ->toEqual($expected);

    expect($data['meta']['totalRowCount'])
        ->toBe(5);

});

/* FN: notEquals */
it('can get correct data with providing filter fn:`notEquals` and datatype:`text`', function () {

    User::factory()
        ->count(4)
        ->create([
            'name' => 'First User'
        ]);

    $expectedUsers = User::factory()
        ->count(6)
        ->create([
            'name' => 'Non match user'
        ]);

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => json_encode([
            [
                'id' => 'name',
                'value' => 'First User',
                'fn' => 'notEquals',
                'datatype' => 'text'
            ]
        ]),
        'sorting' => json_encode([])
    ];

    $allowedFilters = array('name');

    $data = DatatableFacade::run(
        $query,
        $requestParameters,
        $allowedFilters
    );

    $expected = $expectedUsers->toArray();

    expect($data['data'])
        ->toEqual($expected);

    expect($data['meta']['totalRowCount'])
        ->toBe(6);

});

it('can get correct data with providing filter fn:`notEquals` and datatype:`numeric`', function () {

    User::factory()
        ->count(3)
        ->create([
            'age' => 13
        ]);

    $expectedUsers = User::factory()
        ->count(6)
        ->create([
            'age' => 42
        ]);

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => json_encode([
            [
                'id' => 'age',
                'value' => '13',
                'fn' => 'notEquals',
                'datatype' => 'numeric'
            ]
        ]),
        'sorting' => json_encode([])
    ];

    $allowedFilters = array('age');

    $data = DatatableFacade::run(
        $query,
        $requestParameters,
        $allowedFilters
    );

    $expected = $expectedUsers->toArray();

    expect($data['data'])
        ->toEqual($expected);

    expect($data['meta']['totalRowCount'])
        ->toBe(6);

});

it('can get correct data with providing filter fn:`notEquals` and datatype:`date`', function () {

    User::factory()
        ->count(5)
        ->create([
            'created_at' => '2024-05-23 12:00:00'
        ]);


    $expectedUsers = User::factory()
        ->count(6)
        ->create();

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => json_encode([
            [
                'id' => 'created_at',
                'value' => '2024-05-23 12:00:00',
                'fn' => 'notEquals',
                'datatype' => 'date'
            ]
        ]),
        'sorting' => json_encode([])
    ];

    $allowedFilters = array('created_at');

    $data = DatatableFacade::run(
        $query,
        $requestParameters,
        $allowedFilters
    );

    $expected = $expectedUsers->toArray();

    expect($data['data'])
        ->toEqual($expected);

    expect($data['meta']['totalRowCount'])
        ->toBe(6);

});

/* FN: between */
it('can get correct data with providing filter fn:`between` and datatype:`numeric`', function () {

    User::factory()
        ->count(3)
        ->create([
            'age' => 13
        ]);

    $expectedUsers = User::factory()
        ->count(6)
        ->create([
            'age' => 42
        ]);

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => json_encode([
            [
                'id' => 'age',
                'value' => ['30', '50'],
                'fn' => 'between',
                'datatype' => 'numeric'
            ]
        ]),
        'sorting' => json_encode([])
    ];

    $allowedFilters = array('age');

    $data = DatatableFacade::run(
        $query,
        $requestParameters,
        $allowedFilters
    );

    $expected = $expectedUsers->toArray();

    expect($data['data'])
        ->toEqual($expected);

    expect($data['meta']['totalRowCount'])
        ->toBe(6);

});

it('can get correct data with providing filter fn:`between` and datatype:`date`', function () {

    $expectedUsers = User::factory()
        ->count(5)
        ->create([
            'created_at' => '2024-05-23 12:00:00'
        ]);


    User::factory()
        ->count(6)
        ->create();

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => json_encode([
            [
                'id' => 'created_at',
                'value' => ['2024-05-23 10:30:00', '2024-05-23 15:00:00'],
                'fn' => 'between',
                'datatype' => 'date'
            ]
        ]),
        'sorting' => json_encode([])
    ];

    $allowedFilters = array('created_at');

    $data = DatatableFacade::run(
        $query,
        $requestParameters,
        $allowedFilters
    );

    $expected = $expectedUsers->toArray();

    expect($data['data'])
        ->toEqual($expected);

    expect($data['meta']['totalRowCount'])
        ->toBe(5);

});

/* FN: greaterThan */
it('can get correct data with providing filter fn:`greaterThan` and datatype:`numeric`', function () {

    User::factory()
        ->count(3)
        ->create([
            'age' => 13
        ]);

    $expectedUsers = User::factory()
        ->count(6)
        ->create([
            'age' => 42
        ]);

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => json_encode([
            [
                'id' => 'age',
                'value' => '25',
                'fn' => 'greaterThan',
                'datatype' => 'numeric'
            ]
        ]),
        'sorting' => json_encode([])
    ];

    $allowedFilters = array('age');

    $data = DatatableFacade::run(
        $query,
        $requestParameters,
        $allowedFilters
    );

    $expected = $expectedUsers->toArray();

    expect($data['data'])
        ->toEqual($expected);

    expect($data['meta']['totalRowCount'])
        ->toBe(6);

});

it('can get correct data with providing filter fn:`greaterThan` and datatype:`date`', function () {

    User::factory()
        ->count(5)
        ->create([
            'created_at' => '2024-05-23 12:00:00'
        ]);


    $expectedUsers = User::factory()
        ->count(6)
        ->create();

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => json_encode([
            [
                'id' => 'created_at',
                'value' => '2024-05-23 23:59:59',
                'fn' => 'greaterThan',
                'datatype' => 'date'
            ]
        ]),
        'sorting' => json_encode([])
    ];

    $allowedFilters = array('created_at');

    $data = DatatableFacade::run(
        $query,
        $requestParameters,
        $allowedFilters
    );

    $expected = $expectedUsers->toArray();

    expect($data['data'])
        ->toEqual($expected);

    expect($data['meta']['totalRowCount'])
        ->toBe(6);

});

/* FN: lessThan */
it('can get correct data with providing filter fn:`lessThan` and datatype:`numeric`', function () {

    $expectedUsers = User::factory()
        ->count(3)
        ->create([
            'age' => 13
        ]);

    User::factory()
        ->count(6)
        ->create([
            'age' => 42
        ]);

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => json_encode([
            [
                'id' => 'age',
                'value' => '25',
                'fn' => 'lessThan',
                'datatype' => 'numeric'
            ]
        ]),
        'sorting' => json_encode([])
    ];

    $allowedFilters = array('age');

    $data = DatatableFacade::run(
        $query,
        $requestParameters,
        $allowedFilters
    );

    $expected = $expectedUsers->toArray();

    expect($data['data'])
        ->toEqual($expected);

    expect($data['meta']['totalRowCount'])
        ->toBe(3);

});

/** Sorting */
it('throws exception when trying to sort a field which is not allowed to be sorted', function () {

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => json_encode([]),
        'sorting' => json_encode([
            [
                'id' => 'name',
                'desc' => true,
            ]
        ])
    ];

    $data = DatatableFacade::run(
        $query,
        $requestParameters,
    );

})->throws(\HamidRrj\LaravelDatatable\Exceptions\InvalidSortingException::class, "sorting field `name` is not allowed.");

it('can get data with descending sort on age', function (){

    $users = User::factory()
        ->count(6)
        ->create();

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => json_encode([]),
        'sorting' => json_encode([
            [
                'id' => 'age',
                'desc' => true,
            ]
        ])
    ];

    $allowedSortings = array('age');

    $data = DatatableFacade::run(
        $query,
        $requestParameters,
        allowedSortings: $allowedSortings
    );

    $expected = $users->toArray();
    array_multisort( array_column($expected, "age"), SORT_DESC, $expected);

    expect($data['data'])
        ->toEqual($expected);

    expect($data['meta']['totalRowCount'])
        ->toBe(6);
});

it('can get data with ascending sort on age', function (){

    $users = User::factory()
        ->count(6)
        ->create();

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => json_encode([]),
        'sorting' => json_encode([
            [
                'id' => 'age',
                'desc' => false,
            ]
        ])
    ];

    $allowedSortings = array('age');

    $data = DatatableFacade::run(
        $query,
        $requestParameters,
        allowedSortings: $allowedSortings
    );

    $expected = $users->toArray();
    array_multisort( array_column($expected, "age"), SORT_ASC, $expected);

    expect($data['data'])
        ->toEqual($expected);

    expect($data['meta']['totalRowCount'])
        ->toBe(6);
});

// contains: text, numeric DONE     :D
// equals: text, numeric, date      :D
// not equals: text, numeric, date  :D
// between: numeric, date & text    :D
// GT AND LT: numeric , date        :D

//sorting
// filter with rel
// sorting with rel
