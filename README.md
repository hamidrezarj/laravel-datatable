# Laravel Datatable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hamidrrj/laravel-datatable.svg?style=flat-square)](https://packagist.org/packages/hamidrrj/laravel-datatable)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/hamidrezarj/laravel-datatable/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/hamidrezarj/laravel-datatable/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/hamidrezarj/laravel-datatable/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/hamidrezarj/laravel-datatable/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/hamidrrj/laravel-datatable.svg?style=flat-square)](https://packagist.org/packages/hamidrrj/laravel-datatable)

Laravel Datatable is a package designed to handle server-side logic for datatables in Laravel applications. <br>

## Key Features

- Standalone server-side solution for table-like data handling
- Compatible with various frontend table libraries (e.g., [Material React Table](https://www.material-react-table.com/))
- Support for multiple search logics (contains, equals, greater than, etc.) across different data types (numeric, text, date)
- Fine-grained control over searchable, sortable, and visible fields
- Ability to search through model relationships
- Customizable search logic (coming soon!)

## Installation

You can install the package via composer:

```bash
composer require hamidrrj/laravel-datatable
```

After installation, publish the package's service provider using one of the following methods:

####  Option 1: Automatic Installation (Recommended)
Run the following Artisan command:

```bash
php artisan datatable:install
```

#### Option 2: Manual Installation
Publish the provider manually:

```bash
php artisan vendor:publish --tag="datatable-provider"
```

Then, add the following line to the providers array in `config/app.php`:

```bash
return [
    // ...
    'providers' => ServiceProvider::defaultProviders()->merge([
        // ...
        App\Providers\DatatableServiceProvider::class,
        // ...
    ])->toArray(),
    // ...
];
```

## Usage

This section covers various use cases and features of Laravel Datatable. From basic querying to advanced filtering and relationship handling, you'll find examples to help you make the most of this package.

### Table of Contents
- [Method Parameters](#method-parameters)
- [Filter Array Structure](#filter-array-structure)
- [Return Data Structure](#return-data-structure)
- [Basic Usage](#basic-usage)
- [Using Query Builder](#using-query-builder)
- [Advanced Filtering and Sorting](#advanced-filtering-and-sorting)
- [Using `between` Search Function](#using-between-search-function)
- [Filtering Model's Relationship](#filtering-models-relationship)

### Method Parameters

The `run` method of `DatatableFacade` accepts the following parameters:

1. `$mixed`: Model instance or query builder instance to perform queries on.
2. `$requestParameters`: Contains parameters like `filter`, `sorting`, `size`, and `start` of required data.
3. `$allowedFilters`: (Optional) Specifies columns users are allowed to filter on.
4. `$allowedSortings`: (Optional) Specifies columns users are allowed to sort on.
5. `$allowedSelects`: (Optional) Specifies which columns users can actually see.
6. `$allowedRelations`: (Optional) Specifies which model relations users are allowed to filter on.

### Filter Array Structure

Each filter in the `filters` array should have the following attributes:

- `id`: Name of the column to filter on. When filtering a relationship's attribute, use the format: `relationName.attribute`. (`relationName` must exist as a `HasOne` or `HasMany` relationship in the base Model, e.g., User model)
- `value`: Value of the filter
    - For most filter types: a single value
    - For `fn = 'between'`: an array of two values, e.g., `[min, max]`
- `fn`: Type of filter to apply. Available options include:
    - `contains`
    - `between`
    - `equals`
    - `notEquals`
    - `lessThan`
    - `lessThanOrEqual`
    - `greaterThan`
    - `greaterThanOrEqual`
- `datatype`: Type of column. Options include:
    - `text`
    - `numeric`
    - `date`

### Return Data Structure

The `run` method returns an array with the following structure:

```php
[
    "data" => [
        // Array of matching records
    ],
    "meta" => [
        "totalRowCount" => 10 // Total count of matching records
    ]
]
```

### Basic Usage

Here's a simple example of requesting a chunk of 10 users starting from the 11th record (i.e., page 2 of the datatable):

```php
use \HamidRrj\LaravelDatatable\Facades\DatatableFacade;

$userModel = new User();

$requestParameters = [
    'start' => 10,
    'size' => 10,
    'filters' => [],
    'sorting' => []
];

$data = DatatableFacade::run(
    $userModel,
    $requestParameters
);
```

### Using Query Builder
You can use a query builder instance instead of a model instance:
```php
$query = User::query()->where('username', '!=', 'admin');

$data = DatatableFacade::run(
    $query,
    $requestParameters
);
```

### Advanced Filtering and Sorting
Here's an example of filtering users whose ages are greater than 15, sorted by creation date in descending order:
```php
$query = User::query();

$requestParameters = [
    'start' => 10,
    'size' => 10,
    'filters' => [
        [
            'id' => 'age',
            'value' => 15,
            'fn' => 'greaterThan',
            'datatype' => 'numeric'
        ]
    ],
    'sorting' => [
        [
            'id' => 'created_at',
            'desc' => true,
        ]
    ]
];

$allowedFilters = ['age'];
$allowedSortings = ['created_at'];

$data = DatatableFacade::run(
    $query,
    $requestParameters,
    $allowedFilters,
    $allowedSortings
);
```
**Note**: Ensure that columns used for filtering and sorting are included in the `$allowedFilters` and `$allowedSortings` arrays to avoid `InvalidFilterException` and `InvalidSortingException`.

### Using `between` search function
Here's an example of filtering users whose creation dates are between two dates:
```php
$query = User::query()

$requestParameters = [
        'start' => 0,
        'size' => 10,
        'filters' => [
            [
                'id' => 'created_at',
                'value' => ['2024-05-23 10:30:00', '2024-05-29 15:00:00'],
                'fn' => 'between',
                'datatype' => 'date'
            ]
        ],
        'sorting' => []
    ];

$allowedFilters = array('created_at');
$allowedSelects = array('username', 'age', 'created_at');

$data = (new Datatable())->run(
    $query,
    $requestParameters,
    $allowedFilters,
    allowedSelects: $allowedSelects
);
```
**Note**: Using `$allowedSelects` will only return specified columns in the query result:
```php
[
    "data" => [
        [
            'username' => 'mwindler'
            'age' => 49
            'created_at' => '2024-05-23T12:00:00.000000Z' 
        ],
        // more matching records
    ],
    "meta" => [
        "totalRowCount" => 10 // Total count of matching records
    ]
]
```

### Filtering Model's Relationship
In this example, we filter only users who have posts that contain 'my post' in their titles:

```php
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
$allowedRelations = array('posts');

$data = (new Datatable())->run(
    $query,
    $requestParameters,
    $allowedFilters,
    allowedRelations: $allowedRelations
);
```
**Note**: 
- Use `posts.title` in `id` (the User model must have a `posts` relation defined in `Models/User` class)
- Using `$allowedRelations` loads each user's posts in the query result:
```php
[
    "data" => [
        [
            'id' => 1,
            'username' => 'sth', 
            'posts' => [  // posts included in result
                [
                    'title' => 'wow! my post got 1k impressions!'
                ], 
                // ...
            ]
        ],
        // more matching records
    ],
    "meta" => [
        "totalRowCount" => 10 // Total count of matching records
    ]
]
```


## Testing

```bash
composer test
```

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits
- [Hamidreza Ranjbarpour](https://github.com/hamidrezarj)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
