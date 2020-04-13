<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Model\Admin;
use App\Model\Account;
use App\Model\SubUser;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\App\Model\SubscriptionPlan::class, function (Faker $faker) {

    static $number = 1;

    $is_monthly = $faker->boolean;
    $is_annual = $is_monthly ? $faker->boolean : true;
    return [
        'name' => "Plan " . ($number++),
        'desc' => $faker->text,
        'active' => $faker->boolean(90),
        'branches' => $faker->numberBetween(1, 5),
        'ps4' => $faker->numberBetween(1, 5),
        'foods_items' => $faker->numberBetween(1, 5),
        'sub_users' => $faker->numberBetween(1, 5),
        'is_monthly' => $is_monthly,
        'monthly_cost' => $is_monthly ? $faker->randomFloat(0, 10, 200) : 0,
        'is_annual' => $is_annual,
        'annual_cost' => $is_annual ? $faker->randomFloat(0, 100, 20000) : 0,
    ];
});
