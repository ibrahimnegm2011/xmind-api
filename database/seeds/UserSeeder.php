<?php

use Illuminate\Database\Seeder;
use App\Model\Admin;
use App\Model\Account;
use App\Model\Employee;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(Admin::class, 1)->create()->each(function (Admin $admin) {
            $admin->user()->save(factory(User::class)->make(['username' => 'admin']));
        });
        factory(Admin::class, 1)->create()->each(function (Admin $admin) {
            $admin->user()->save(factory(User::class)->make(['username' => 'khaled']));
        });
        factory(Admin::class, 1)->create()->each(function (Admin $admin) {
            $admin->user()->save(factory(User::class)->make(['username' => 'hamada']));
        });

        factory(Account::class, 1)->create(['plan_id' => 1])->each(function (Account $account) {
            $account->user()->save(factory(User::class)->make(['username' => 'xmind']));

            factory(Employee::class, 1)->create(['account_id' => $account->id])->each(function (Employee $emp) {
                $emp->user()->save(factory(User::class)->make(['username' => 'ali']));
            });
            factory(Employee::class, 1)->create(['account_id' => $account->id])->each(function (Employee $emp) {
                $emp->user()->save(factory(User::class)->make(['username' => 'jack']));
            });
        });

        factory(Account::class, 4)->create(['plan_id' => 2])->each(function (Account $account) {
            $account->user()->save(factory(User::class)->make());

            if (rand(0, 1) != 1) return;

            factory(Employee::class, 1)->create(['account_id' => $account->id])->each(function (Employee $emp) {
                $emp->user()->save(factory(User::class)->make());
            });
        });
    }
}
