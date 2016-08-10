<?php

namespace Travoltron\CashierExtras;

use Illuminate\Support\ServiceProvider;

class CashierExtrasServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Travoltron\CashierExtras\Commands\CreateCoupon',
        // 'Travoltron\CashierExtras\Commands\FooCommand',
        // 'Travoltron\CashierExtras\Commands\BarCommand',
    ];
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands($this->commands);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('plaid',function($app){
            return new Plaid($app);
        });
    }
}