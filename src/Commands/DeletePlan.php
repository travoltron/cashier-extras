<?php

namespace Travoltron\CashierExtras\Commands;

use Carbon\Carbon;
use InvalidArgumentException;
use Stripe\Stripe as Stripe;
use Stripe\Plan as StripePlan;
use Illuminate\Console\Command;
use Stripe\Error\InvalidRequest as StripeErrorInvalidRequest;

class DeletePlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:delete-plan {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete plan from your Stripe account';

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
        // Delete the plan
        StripePlan::retrieve($this->argument('id'))->delete();
        // Show the table
        $headers = ['ID', 'Name', 'Amount', 'Currency', 'Repeats every', 'Trial length', 'Appears as', 'Created on'];
        $collection = collect(StripePlan::all()->__toArray(true)['data']);
        if($collection->isEmpty()) {
            return $this->info('No plans found.');
        }
        $i = 0;
        foreach($collection as $plan) {
            $plans[$i]['id'] = $plan['id'];
            $plans[$i]['name'] = $plan['name'];
            $plans[$i]['amount'] = money_format('%2n', $plan['amount']/100);
            $plans[$i]['currency'] = $plan['currency'];
            $plans[$i]['repeats'] = $plan['interval_count'].' '.str_plural($plan['interval'], $plan['interval_count']);
            $plans[$i]['trial_period_days'] = $plan['trial_period_days'];
            $plans[$i]['statement'] = $plan['statement_descriptor'];
            $plans[$i]['created'] = Carbon::createFromTimestamp($plan['created'])->format('m-d-Y');
            $i++;
        }
        $this->table($headers, $plans);
    }
}
