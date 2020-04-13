<?php

use Illuminate\Database\Seeder;
use App\Model\SubscriptionPlan;

class SubscriptionPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(SubscriptionPlan::class, 3)->create();
    }
}
