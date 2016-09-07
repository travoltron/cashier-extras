<?php

namespace Travoltron\CashierExtras\Commands;

use Illuminate\Console\Command;
use Stripe\Coupon as StripeCoupon;
use Stripe\Plan as StripePlan;
use Stripe\Stripe as Stripe;

class MakeTestable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cashier:test-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create needed Stripe data for testing Cashier';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Setting test plans and coupons at Stripe');
        // Check that the keys are set
        if (!env('STRIPE_TEST_KEY')) {
            $this->error('Make sure to set \'STRIPE_TEST_KEY\' in your .env file!');
        }
        if (!env('STRIPE_TEST_SECRET')) {
            $this->error('Make sure to set \'STRIPE_TEST_SECRET\' in your .env file!');
        }
        if (!env('STRIPE_TEST_SECRET') && !env('STRIPE_TEST_KEY')) {
            return;
        }
        // Check that the keys are set correctly
        if (stristr(env('STRIPE_TEST_KEY'), 'pk_test_') === false) {
            $this->error('\'STRIPE_TEST_KEY\' is set incorrectly!');
        }
        if (stristr(env('STRIPE_TEST_SECRET'), 'sk_test_') === false) {
            $this->error('\'STRIPE_TEST_SECRET\' is set incorrectly!');
        }
        if (stristr(env('STRIPE_TEST_KEY'), '_test_') === false && stristr(env('STRIPE_TEST_SECRET'), '_test_') === false) {
            return;
        }
        // Test keys are set and appear to be correct
        Stripe::setApiKey(env('STRIPE_TEST_SECRET'));
        StripePlan::create([
            'amount'   => 1000,
            'interval' => 'month',
            'name'     => 'monthly-10-1',
            'currency' => 'usd',
            'id'       => 'monthly-10-1',
        ]);
        StripePlan::create([
            'amount'   => 1000,
            'interval' => 'month',
            'name'     => 'monthly-10-2',
            'currency' => 'usd',
            'id'       => 'monthly-10-2',
        ]);
        StripeCoupon::create([
            'amount_off' => 500,
            'duration'   => 'once',
            'currency'   => 'usd',
            'id'         => 'coupon-1',
        ]);
        $this->info('Successfully created plans and coupons for testing.');
    }
}
