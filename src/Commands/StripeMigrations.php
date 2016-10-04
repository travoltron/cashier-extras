<?php

namespace Travoltron\CashierExtras\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StripeMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cashier:run-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run needed database migration';

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
        \Schema::table('users', function ($table) {
            if (!\Schema::hasColumn('users', 'stripe_id')) {
                $table->string('stripe_id')->nullable();
            }
            if (!\Schema::hasColumn('users', 'card_brand')) {
                $table->string('card_brand')->nullable();
            }
            if (!\Schema::hasColumn('users', 'card_last_four')) {
                $table->string('card_last_four')->nullable();
            }
            if (!\Schema::hasColumn('users', 'trial_ends_at')) {
                $table->string('trial_ends_at')->nullable();
            }
        });
        if (!\Schema::hasTable('subscriptions')) {
            \Schema::create('subscriptions', function ($table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('name');
                $table->string('stripe_id');
                $table->string('stripe_plan');
                $table->integer('quantity');
                $table->timestamp('trial_ends_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->timestamps();
            });
        }
        $this->info('Successfully run migration.');
    }
}
