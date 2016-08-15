# cashier-extras

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]


## Install

Via Composer

``` bash
$ composer require travoltron/cashier-extras
```

Add the following to `config/app.php`:

`Travoltron\CashierExtras\CashierExtrasServiceProvider::class,`

## Usage

This package adds a few interactive CLI tools to make working with Stripe and Laravel's Cashier package a little bit easier. For now it supports creating, listing, and deleting both Stripe Plans and Coupons. 

The Laravel Cashier package documentation states that to run it's own suite of tests, some plans and a coupon need to be added to your Stripe account. 

The added commands are as follows:

###Cashier Testing

``` bash
php artisan cashier:test-data
```

This adds the needed plans and coupons for Laravel Cashier testing.

###Check Stripe Keys

``` bash
php artisan stripe:check-keys
```

This checks that the `.env` file has been populated with the correct keys and checks at a very loose level that the keys are correctly formatted.

###Stripe Plans

``` bash
php artisan stripe:list-plans
```

Displays a table of the plans currently enabled on your Stripe account.

``` bash
php artisan stripe:make-plan
```

Interactive wizard to create a plan via the CLI.

``` bash
php artisan stripe:delete-plan {id}
```

Deletes a plan with the supplied id. To see this ID, list the plans, and select the value from the first column.


###Stripe Coupons

``` bash
php artisan stripe:list-coupons
```

Displays a table of the coupons currently enabled on your Stripe account.

``` bash
php artisan stripe:make-coupon
```

Interactive wizard to create a coupon via the CLI.

``` bash
php artisan stripe:delete-coupon {id}
```

Deletes a coupon with the supplied id. To see this ID, list the coupons, and select the value from the first column.

##Caveats 

By default, the `config/services.php` file has a section for Stripe setup like so:

``` php
'stripe' => [
    'model' => App\User::class,
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
],
```

In order to check for the keys being set properly, change this to: 
``` php
'stripe' => [
    'model' => App\User::class,
    'key' => env('STRIPE_KEY', env('STRIPE_TEST_KEY')),
    'secret' => env('STRIPE_SECRET', env('STRIPE_TEST_SECRET')),
],
```

and add the keys `STRIPE_TEST_KEY` and `STRIPE_TEST_SECRET` to your .env file. 

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email ben@travoltron.com instead of using the issue tracker.

## Credits

- [Ben Warburton][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/travoltron/cashier-extras.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/travoltron/cashier-extras.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/travoltron/cashier-extras
[link-downloads]: https://packagist.org/packages/travoltron/cashier-extras
[link-author]: https://github.com/travoltron
[link-contributors]: ../../contributors
