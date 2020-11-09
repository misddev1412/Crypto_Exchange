<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->string('trade_coin', 10)->index();
            $table->string('base_coin', 10)->index();
            $table->string('trade_pair', 20)->index();
            $table->string('category', 20)->index();
            $table->string('type', 20)->index();
            $table->decimal('price', 19, 8)->unsigned()->nullable();
            $table->decimal('amount', 19, 8)->unsigned()->nullable();
            $table->decimal('total', 19, 8)->unsigned()->nullable();
            $table->decimal('exchanged', 19, 8)->unsigned()->default(0);
            $table->decimal('canceled', 19, 8)->unsigned()->default(0);
            $table->decimal('stop_limit', 19, 8)->unsigned()->nullable();
            $table->decimal('maker_fee_in_percent', 5, 2)->unsigned();
            $table->decimal('taker_fee_in_percent', 5, 2)->unsigned();
            $table->string('status', 20)->default(STATUS_PENDING)->index();
            $table->timestamps();

            $table->index(['user_id', 'trade_pair']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');

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
        Schema::dropIfExists('orders');
    }
}
