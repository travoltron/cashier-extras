<?php

namespace Travoltron\CashierExtras\Commands;

use Carbon\Carbon;
use InvalidArgumentException;
use Stripe\Stripe as Stripe;
use Stripe\Coupon as StripeCoupon;
use Illuminate\Console\Command;
use Stripe\Error\InvalidRequest as StripeErrorInvalidRequest;

class ListCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:list-coupons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List coupons from your Stripe account';

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
        $this->info('Listing your coupons at Stripe');
        // Check that the keys are set
        $valid = [
            'test' => true,
            'live' => true
        ];
        // Check that the keys are set correctly
        if (stristr(env('STRIPE_TEST_KEY'), '_test_') === false && stristr(env('STRIPE_TEST_SECRET'), '_test_') === false) {
            $valid['test'] = false;
            $this->error('Stripe test keys are incorrectly set.');
        }
        if (stristr(env('STRIPE_TEST_KEY'), '_test_') !== false && stristr(env('STRIPE_TEST_SECRET'), '_test_') !== false) {
            $this->info('Stripe test keys are correctly set.');
        }
        if (stristr(env('STRIPE_KEY'), '_live_') === false && stristr(env('STRIPE_SECRET'), '_live_') === false) {
            $valid['live'] = false;
            $this->error('Stripe live keys are incorrectly set.');
        }
        if (stristr(env('STRIPE_KEY'), '_live_') !== false && stristr(env('STRIPE_SECRET'), '_live_') !== false) {
            $this->info('Stripe live keys are correctly set.');
        }
        $envs = collect($valid)->filter(function ($val, $key) {
                return $val === true;
            })->keys()->map(function ($keys) {
                return ucfirst($keys);
            })->toArray();
        if(empty($envs)) {
            $this->error('Your keys are missing or set incorrectly.');
            return;
        }
        $env = $this->choice('Which Stripe environment to use?', $envs);

        // Test keys are set and appear to be correct
        Stripe::setApiKey(($env == 'Test')?env('STRIPE_TEST_SECRET'):env('STRIPE_SECRET'));
        $headers = ['ID', 'Discount', 'Currency', 'Duration', 'Lasts for ', 'Able to be used', 'Has been used', 'Created on'];
        $collection = collect(StripeCoupon::all()->__toArray(true)['data']);
        if($collection->isEmpty()) {
            return $this->info('No coupons found.');
        }
        $i = 0;
        foreach($collection as $coupon) {
            $coupons[$i]['id'] = $coupon['id'];
            $coupons[$i]['discount'] = ($coupon['amount_off'] !== null)?'$'.$coupon['amount_off']/100:$coupon['percent_off'].'%';
            $coupons[$i]['currency'] = strtoupper($coupon['currency']);
            $coupons[$i]['duration'] = ucfirst($coupon['duration']);
            $coupons[$i]['repeats'] = ($coupon['duration_in_months'])?str_plural($coupon['duration_in_months']):'Forever';
            $coupons[$i]['available'] = $coupon['max_redemptions'];
            $coupons[$i]['used'] = $coupon['times_redeemed'];
            $coupons[$i]['created'] = Carbon::createFromTimestamp($coupon['created'])->format('m-d-Y');
            $i++;
        }
        $this->table($headers, $coupons);
    }
}
