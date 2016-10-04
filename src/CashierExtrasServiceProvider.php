<?php

namespace Travoltron\CashierExtras;

use Illuminate\Support\ServiceProvider;

class CashierExtrasServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Travoltron\CashierExtras\Commands\CheckKeys',
        'Travoltron\CashierExtras\Commands\MakeTestable',
        'Travoltron\CashierExtras\Commands\CreatePlan',
        'Travoltron\CashierExtras\Commands\ListPlans',
        'Travoltron\CashierExtras\Commands\DeletePlan',
        'Travoltron\CashierExtras\Commands\CreateCoupon',
        'Travoltron\CashierExtras\Commands\ListCoupons',
        'Travoltron\CashierExtras\Commands\DeleteCoupon',
        'Travoltron\CashierExtras\Commands\StripeMigrations',
        'Travoltron\CashierExtras\Commands\StripeMigrationsRollback',
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
     * @return void
     */
    public function register()
    {
        //
    }
}
