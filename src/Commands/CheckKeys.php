<?php

namespace Travoltron\CashierExtras\Commands;

use InvalidArgumentException;
use Illuminate\Console\Command;
use Stripe\Coupon as StripeCoupon;
use Stripe\Error\InvalidRequest as StripeErrorInvalidRequest;

class CreateCoupon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:check-keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new coupon to use with your Stripe account';

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
        $valid = [
            'test' => true,
            'live' => true
        ];
        // Check that the keys are set correctly
        if (stristr(env('STRIPE_TEST_KEY'), '_test_') === false && stristr(env('STRIPE_TEST_SECRET'), '_test_') === false) {
            $valid['test'] = false;
            if (!env('STRIPE_TEST_KEY')) {
                $valid['test'] = false;
                $this->error('Make sure to set \'STRIPE_TEST_KEY\' in your .env file!');
            }
            if (!env('STRIPE_TEST_SECRET')) {
                $valid['test'] = false;
                $this->error('Make sure to set \'STRIPE_TEST_SECRET\' in your .env file!');
            }
            $this->error('Stripe test keys are incorrectly set.');
        }
        if (stristr(env('STRIPE_TEST_KEY'), '_test_') !== false && stristr(env('STRIPE_TEST_SECRET'), '_test_') !== false) {
            $valid['test'] = false;
            if (!env('STRIPE_KEY')) {
                $valid['live'] = false;
                $this->error('Make sure to set \'STRIPE_KEY\' in your .env file!');
            }
            if (!env('STRIPE_SECRET')) {
                $valid['live'] = false;
                $this->error('Make sure to set \'STRIPE_SECRET\' in your .env file!');
            }
            $this->info('Stripe test keys are correctly set.');
        }
        if (stristr(env('STRIPE_KEY'), '_live_') === false && stristr(env('STRIPE_SECRET'), '_live_') === false) {
            $valid['live'] = false;
            $this->error('Stripe live keys are incorrectly set.');
        }
        if (stristr(env('STRIPE_KEY'), '_live_') !== false && stristr(env('STRIPE_SECRET'), '_live_') !== false) {
            $valid['live'] = false;
            $this->info('Stripe live keys are correctly set.');
        }
        return $valid;
    }
}
