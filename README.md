# Laravel React Datatable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hamidrrj/laravel-datatable.svg?style=flat-square)](https://packagist.org/packages/hamidrrj/laravel-datatable)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/hamidrrj/laravel-datatable/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/hamidrrj/laravel-datatable/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/hamidrrj/laravel-datatable/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/hamidrrj/laravel-datatable/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/hamidrrj/laravel-datatable.svg?style=flat-square)](https://packagist.org/packages/hamidrrj/laravel-datatable)

This package is implemented for dealing with Material React Table queries.
It contains various search logics for different datatypes (numeric, text, date):
1. Contains
2. Between
3. LessThan
4. GreaterThan
5. Equals
6. NotEquals

You can add your own search logic and datatype with ease :)

## Installation

You can install the package via composer:

```bash
composer require hamidrrj/laravel-datatable
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-datatable-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-datatable-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-datatable-views"
```

## Usage

```php
$laravelDatatable = new HamidRrj\LaravelDatatable();
echo $laravelDatatable->echoPhrase('Hello, HamidRrj!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Hamidreza Ranjbarpour](https://github.com/hamidrezarj)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
