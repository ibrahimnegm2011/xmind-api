<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Model\Admin;
use App\Model\Account;
use App\Model\Employee;
use App\User;

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

$factory->define(Admin::class, function (Faker $faker) {
    return [];
});

$factory->define(Account::class, function (Faker $faker) {
    return [];
});

$factory->define(Employee::class, function (Faker $faker) {
    return [];
});

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'username' => $faker->userName,
        'phone' => $faker->phoneNumber,
        'password' => app('hash')->make('123456')
    ];
});
