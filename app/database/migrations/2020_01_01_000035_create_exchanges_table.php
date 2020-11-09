<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->uuid('order_id');
            $table->string('trade_coin', 10);
            $table->string('base_coin', 10);
            $table->string('trade_pair', 20)->index();
            $table->decimal('amount', 19, 8)->unsigned();
            $table->decimal('price', 19, 8)->unsigned();
            $table->decimal('total', 19, 8)->unsigned();
            $table->decimal('fee', 19, 8)->unsigned();
            $table->decimal('referral_earning', 19, 8)->default(0);
            $table->string('order_type')->index();
            $table->uuid('related_order_id')->nullable();
            $table->integer('is_maker');
            $table->timestamps();

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

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('related_order_id')
                ->references('id')
                ->on('orders')
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
        Schema::dropIfExists('exchanges');
    }
}
