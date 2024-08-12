<?php

use HamidRrj\LaravelDatatable\Facades\Datatable;
use \HamidRrj\LaravelDatatable\Tests\Models\User;
use \HamidRrj\LaravelDatatable\Tests\Models\Post;

it('can get data with empty filters', function () {

    $users = User::factory()->count(5)->create();
    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => [],
        'sorting' => []
    ];

    $data = (new Datatable())->run(
        $query,
        $requestParameters
    );

    expect($data['data'])
        ->toEqual($users->toArray());

    expect($data['meta']['totalRowCount'])
        ->toBe(5);


});

it('can get data using model instance instead of query builder', function () {

    $users = User::factory()->count(5)->create();

    $userModel = new User();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => [],
        'sorting' => []
    ];

    $data = (new Datatable())->run(
        $userModel,
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
        'filters' => [],
        'sorting' => []
    ];

    $data = (new Datatable())->run(
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
        'filters' => [
            [
                'id' => 'username',
                'value' => 'sth',
                'fn' => 'contains',
                'datatype' => 'text'
            ]
        ],
        'sorting' => []
    ];

    $data = (new Datatable())->run(
        $query,
        $requestParameters
    );

})->throws(\HamidRrj\LaravelDatatable\Exceptions\InvalidFilterException::class, "filtering field `username` is not allowed.");

it('throws exception when provided filter fn is not defined and is invalid', function () {

    $query = User::query();

    $requestParameters = [
        'start' => 5,
        'size' => 8,
        'filters' => [
            [
                'id' => 'username',
                'value' => 'sth',
                'fn' => 'sth',
                'datatype' => 'text'
            ]
        ],
        'sorting' => []
    ];

    $allowedFilters = array('username');

    $data = (new Datatable())->run(
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
        'filters' => [
            [
                'id' => 'username',
                'value' => 'sth',
                'fn' => 'notEquals',
                'datatype' => 'sth'
            ]
        ],
        'sorting' => []
    ];

    $allowedFilters = array('username');

    $data = (new Datatable())->run(
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
        'filters' => [
            [
                'id' => 'name',
                'value' => 'first',
                'fn' => 'contains',
                'datatype' => 'text'
            ]
        ],
        'sorting' => []
    ];

    $allowedFilters = array('name');

    $data = (new Datatable())->run(
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
        'filters' => [
            [
                'id' => 'age',
                'value' => '3',
                'fn' => 'contains',
                'datatype' => 'numeric'
            ]
        ],
        'sorting' => []
    ];

    $allowedFilters = array('age');

    $data = (new Datatable())->run(
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
        'filters' => [
            [
                'id' => 'name',
                'value' => 'first user',
                'fn' => 'equals',
                'datatype' => 'text'
            ]
        ],
        'sorting' => []
    ];

    $allowedFilters = array('name');

    $data = (new Datatable())->run(
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
        'filters' => [
            [
                'id' => 'age',
                'value' => '13',
                'fn' => 'equals',
                'datatype' => 'numeric'
            ]
        ],
        'sorting' => []
    ];

    $allowedFilters = array('age');

    $data = (new Datatable())->run(
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
        'filters' => [
            [
                'id' => 'created_at',
                'value' => '2024-05-23 12:00:00',
                'fn' => 'equals',
                'datatype' => 'date'
            ]
        ],
        'sorting' => []
    ];

    $allowedFilters = array('created_at');

    $data = (new Datatable())->run(
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
        'filters' => [
            [
                'id' => 'name',
                'value' => 'First User',
                'fn' => 'notEquals',
                'datatype' => 'text'
            ]
        ],
        'sorting' => []
    ];

    $allowedFilters = array('name');

    $data = (new Datatable())->run(
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
        'filters' => [
            [
                'id' => 'age',
                'value' => '13',
                'fn' => 'notEquals',
                'datatype' => 'numeric'
            ]
        ],
        'sorting' => []
    ];

    $allowedFilters = array('age');

    $data = (new Datatable())->run(
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
        'filters' => [
            [
                'id' => 'created_at',
                'value' => '2024-05-23 12:00:00',
                'fn' => 'notEquals',
                'datatype' => 'date'
            ]
        ],
        'sorting' => []
    ];

    $allowedFilters = array('created_at');

    $data = (new Datatable())->run(
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
        'filters' => [
            [
                'id' => 'age',
                'value' => ['30', '50'],
                'fn' => 'between',
                'datatype' => 'numeric'
            ]
        ],
        'sorting' => []
    ];

    $allowedFilters = array('age');

    $data = (new Datatable())->run(
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

it('can get correct data with providing filter fn:`between` and datatype:`date` with selecting only specific columns', function () {

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
        'filters' => [
            [
                'id' => 'created_at',
                'value' => ['2024-05-23 10:30:00', '2024-05-23 15:00:00'],
                'fn' => 'between',
                'datatype' => 'date'
            ]
        ],
        'sorting' => []
    ];

    $allowedFilters = array('created_at');
    $allowedSelects = array('username', 'email', 'age');

    $data = (new Datatable())->run(
        $query,
        $requestParameters,
        $allowedFilters,
        allowedSelects: $allowedSelects
    );

    $expected = $expectedUsers->map->only($allowedSelects)->toArray();

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
        'filters' => [
            [
                'id' => 'age',
                'value' => '25',
                'fn' => 'greaterThan',
                'datatype' => 'numeric'
            ]
        ],
        'sorting' => []
    ];

    $allowedFilters = array('age');

    $data = (new Datatable())->run(
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
        'filters' => [
            [
                'id' => 'created_at',
                'value' => '2024-05-23 23:59:59',
                'fn' => 'greaterThan',
                'datatype' => 'date'
            ]
        ],
        'sorting' => []
    ];

    $allowedFilters = array('created_at');

    $data = (new Datatable())->run(
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
        'filters' => [
            [
                'id' => 'age',
                'value' => '25',
                'fn' => 'lessThan',
                'datatype' => 'numeric'
            ]
        ],
        'sorting' => []
    ];

    $allowedFilters = array('age');

    $data = (new Datatable())->run(
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
        'filters' => [],
        'sorting' => [
            [
                'id' => 'name',
                'desc' => true,
            ]
        ]
    ];

    $data = (new Datatable())->run(
        $query,
        $requestParameters,
    );

})->throws(\HamidRrj\LaravelDatatable\Exceptions\InvalidSortingException::class, "sorting field `name` is not allowed.");

it('can get data with descending sort on age', function () {

    $users = User::factory()
        ->count(6)
        ->create();

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => [],
        'sorting' => [
            [
                'id' => 'age',
                'desc' => true,
            ]
        ]
    ];

    $allowedSortings = array('age');

    $data = (new Datatable())->run(
        $query,
        $requestParameters,
        allowedSortings: $allowedSortings
    );

    $expected = $users->toArray();
    array_multisort(array_column($expected, "age"), SORT_DESC, $expected);

    expect($data['data'])
        ->toEqual($expected);

    expect($data['meta']['totalRowCount'])
        ->toBe(6);
});

it('can get data with ascending sort on age', function () {

    $users = User::factory()
        ->count(6)
        ->create();

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => [],
        'sorting' => [
            [
                'id' => 'age',
                'desc' => false,
            ]
        ]
    ];

    $allowedSortings = array('age');

    $data = (new Datatable())->run(
        $query,
        $requestParameters,
        allowedSortings: $allowedSortings
    );

    $expected = $users->toArray();
    array_multisort(array_column($expected, "age"), SORT_ASC, $expected);

    expect($data['data'])
        ->toEqual($expected);

    expect($data['meta']['totalRowCount'])
        ->toBe(6);
});

it("can get correct data with providing filter for model's relation with fn:`contains` and datatype:`text`", function () {

    $expectedUsers = User::factory()
        ->count(3)
        ->has(Post::factory()
            ->state(new \Illuminate\Database\Eloquent\Factories\Sequence(
                ['title' => 'Wow! My post got 10k impressions'],
            ))
        )
        ->create();

    User::factory()
        ->count(4)
        ->has(Post::factory()
            ->state(new \Illuminate\Database\Eloquent\Factories\Sequence(
                ['title' => 'Lorem ipsum doler sit emet.'],
            ))
        )
        ->create();

    $query = User::query();

    $requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => [
            [
                'id' => 'posts.title',
                'value' => 'my post',
                'fn' => 'contains',
                'datatype' => 'text'
            ]
        ],
        'sorting' => []
    ];

    $allowedFilters = array('posts.title');

    $data = (new Datatable())->run(
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

// contains: text, numeric DONE     :D
// equals: text, numeric, date      :D
// not equals: text, numeric, date  :D
// between: numeric, date & text    :D
// GT AND LT: numeric , date        :D

//sorting
// filter with rel
// include relations test :)
// allowed select test
