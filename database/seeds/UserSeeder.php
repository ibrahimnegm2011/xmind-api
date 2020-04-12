<?php

use Illuminate\Database\Seeder;
use App\Model\Admin;
use App\Model\Account;
use App\Model\SubUser;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Admin::class)->create(['username' => 'admin']);
        factory(Admin::class)->create(['username' => 'khaled']);
        factory(Admin::class)->create(['username' => 'hamada']);

        factory(Account::class, 5)->create()->each(function (Account $account) {
            if (rand(0, 1) != 1) return;
            $account->subUsers()->save(factory(SubUser::class)->make());
        });
    }
}
