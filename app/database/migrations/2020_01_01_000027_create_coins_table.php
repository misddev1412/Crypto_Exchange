<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coins', function (Blueprint $table) {
            $table->string('symbol', 10)->primary();
            $table->string('name', 100);
            $table->string('type', 20);
            $table->string('icon')->nullable();
            $table->boolean('exchange_status')->default(ACTIVE);

            //Deposit Fields
            $table->boolean('deposit_status')->default(INACTIVE);
            $table->decimal('deposit_fee', 13, 2)->unsigned()->default(0);
            $table->string('deposit_fee_type', 20)->default(FEE_TYPE_FIXED);
            $table->decimal('minimum_deposit_amount', 19, 8)->unsigned()->nullable();
            $table->decimal('total_deposit', 19, 8)->unsigned()->default(0);
            $table->decimal('total_deposit_fee', 19, 8)->unsigned()->default(0);

            //Withdrawal Fields
            $table->boolean('withdrawal_status')->default(INACTIVE);
            $table->decimal('withdrawal_fee', 13, 2)->unsigned()->default(0);
            $table->string('withdrawal_fee_type', 20)->default(FEE_TYPE_FIXED);
            $table->decimal('minimum_withdrawal_amount', 19, 8)->unsigned()->nullable();
            $table->decimal('daily_withdrawal_limit', 19, 8)->unsigned()->nullable();
            $table->decimal('total_withdrawal', 19, 8)->unsigned()->default(0);
            $table->decimal('total_withdrawal_fee', 19, 8)->unsigned()->default(0);

            $table->json('api')->nullable();
            $table->boolean('is_active')->default(ACTIVE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coins');
    }
}
