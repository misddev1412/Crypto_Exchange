<?php

use App\Models\Core\Notice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class NoticesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cache::forget('notices');
        factory(Notice::class, 3)->create();
    }
}
