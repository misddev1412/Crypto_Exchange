<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CoinsTableSeeder::class);
        $this->call(NotificationsTableSeeder::class);
        $this->call(NoticesTableSeeder::class);
        $this->call(TicketsTableSeeder::class);
        $this->call(OrdersTableSeeder::class);
        $this->call(BankAccountsTableSeeder::class);
        $this->call(DepositsTableSeeder::class);
        $this->call(WithdrawalsTableSeeder::class);
        $this->call(PostsTableSeeder::class);
    }
}
