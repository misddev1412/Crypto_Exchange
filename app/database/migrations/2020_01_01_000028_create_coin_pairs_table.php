<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinPairsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_pairs', function (Blueprint $table) {
            $table->string('name', 20)->primary();
            $table->string('trade_coin', 10);
            $table->string('base_coin', 10);
            $table->integer('is_active')->default(ACTIVE);
            $table->integer('is_default')->default(INACTIVE);
            $table->decimal('last_price', 19, 8)->unsigned()->default(0);
            $table->timestamps();

            $table->unique(['trade_coin', 'base_coin']);

            $table->foreign('trade_coin')
                ->references('symbol')
                ->on('coins')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('base_coin')
                ->references('symbol')
                ->on('coins')
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
        Schema::dropIfExists('coin_pairs');
    }
}
