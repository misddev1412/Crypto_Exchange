<?php

use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketComment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TicketsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('tickets')->truncate();
        DB::table('ticket_comments')->truncate();
        Schema::enableForeignKeyConstraints();

        factory(Ticket::class, 10)->create()->each(function ($ticket) {
            $ticket->comments()->saveMany(
                factory(TicketComment::class, random_int(1, 4))->make(['ticket_id' => $ticket->id])
            );
        });
    }
}
