<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_data', function (Blueprint $table) {
            $table->date('date');
            $table->string('trade_pair', 20)->index();
            $table->text('5min');
            $table->text('15min');
            $table->text('30min');
            $table->text('2hr');
            $table->text('4hr');
            $table->text('1day');

            $table->primary(['date', 'trade_pair']);

            $table->foreign('trade_pair')
                ->references('name')
                ->on('coin_pairs')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_data');
    }
}
