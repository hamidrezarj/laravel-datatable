# Laravel Datatable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hamidrezarj/laravel-datatable.svg?style=flat-square)](https://packagist.org/packages/hamidrezarj/laravel-datatable)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/hamidrezarj/laravel-datatable/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/hamidrezarj/laravel-datatable/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/hamidrezarj/laravel-datatable/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/hamidrezarj/laravel-datatable/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/hamidrezarj/laravel-datatable.svg?style=flat-square)](https://packagist.org/packages/hamidrezarj/laravel-datatable)

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
Here's an example of filtering users whose usernames contain 'sth', sorted by creation date in descending order:
```php
$requestParameters = [
    'start' => 10,
    'size' => 10,
    'filters' => [
        [
            'id' => 'username',
            'value' => 'sth',
            'fn' => 'contains',
            'datatype' => 'text'
        ]
    ],
    'sorting' => [
        [
            'id' => 'created_at',
            'desc' => true,
        ]
    ]
];

$allowedFilters = ['username'];
$allowedSortings = ['created_at'];

$data = DatatableFacade::run(
    $query,
    $requestParameters,
    $allowedFilters,
    $allowedSortings
);
```
**Note**: Ensure that columns used for filtering and sorting are included in the `$allowedFilters` and `$allowedSortings` arrays to avoid `InvalidFilterException` and `InvalidSortingException`.

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
- `id`: Name of the column to filter on
- `value`: Value of the filter
- `fn`: Type of filter to apply (e.g., contains, between, equals, less than)
- `datatype`: Type of column (e.g., text, numeric, date)

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
