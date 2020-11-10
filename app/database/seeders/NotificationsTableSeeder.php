<?php

namespace Database\Seeders;

use App\Models\Core\Notification;
use Illuminate\Database\Seeder;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        Notification::factory()->count(10)->create();
    }
}
