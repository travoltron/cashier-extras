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
        
    }
}
