<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->unique();
            $table->text('desc');
            $table->boolean('active');
            $table->integer('branches')->default(0);
            $table->integer('ps4')->default(0);
            $table->integer('foods_items')->default(0);
            $table->integer('sub_users')->default(0);
            $table->boolean('is_monthly')->default(true);
            $table->decimal('monthly_cost', 8, 2, true);
            $table->boolean('is_annual')->default(true);
            $table->decimal('annual_cost', 8, 2, true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::drop('subscription_plans');
    }
}
