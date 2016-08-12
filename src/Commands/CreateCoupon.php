<?php

namespace Travoltron\CashierExtras\Commands;

use Carbon\Carbon;
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
    protected $signature = 'stripe:make-coupon';

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
        $this->info('Creating a new coupon at Stripe');
        // Check that the keys are set
        $validKeys = Artisan::call('stripe:check-keys');
        $envs = collect($validKeys)->filter(function($val, $key) {
            return $val === true;
        });
        $env = $this->choice('Which Stripe environment to use?', $envs);

        // Test keys are set and appear to be correct
        Stripe::setApiKey(($env == 0)?env('STRIPE_TEST_SECRET'):env('STRIPE_SECRET'));
        $type = $this->ask('Discount type', ['Percentage', 'Fixed Amount']);
        if($type == 'Percentage') {
            $data['percent_off'] = $this->ask('Percentage discount:');
        }
        if($type == 'Fixed Amount') {
            $data['amount_off'] = $this->ask('Discount amount:');
            $data['currency'] = $this->ask('Currency code:', 'usd');

        }
        $duration = $this->choice('How long should this coupon work?', ['Forever', 'Once', 'Repeating']);
        if($duration == 'Repeating') {
            $data['duration_in_months'] = $this->ask('How many months should this work for? (numeric)');
        }
        $data['id'] = $this->ask('What is the coupon code to use? (ex. FALLSALE50)');
        $data['duration'] = strtolower($duration);
        $data['max_redemptions'] = $this->ask('How many times can this coupon be used? (numeric)');
        $data['redeem_by'] = Carbon::parse($this->ask('When does this coupon expire? (MM-DD-YYYY)'))->timestamp;

        StripeCoupon::create($data);
        $this->info('Successfully created plans and coupons for testing.');
        return;
    }
}
