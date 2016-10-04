<?php

namespace Travoltron\CashierExtras\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Migrations\Migration;

class StripeMigrationsRollback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cashier:rollback-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback database migrations for Stripe Cashier';

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
        \Schema::dropIfExists('subscriptions');
        \Schema::table('users', function ($table) {
            $table->dropColumn(['stripe_id', 'trial_ends_at', 'card_brand', 'card_last_four']);
        });
        $this->info('Successfully rolled back migration.');
    }
}
