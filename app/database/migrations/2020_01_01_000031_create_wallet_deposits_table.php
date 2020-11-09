<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_deposits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->uuid('wallet_id')->index();
            $table->uuid('bank_account_id')->nullable();
            $table->string('symbol', 10);
            $table->uuid('system_bank_account_id')->nullable();
            $table->string('address')->nullable();
            $table->decimal('amount', 19, 8)->unsigned();
            $table->decimal('system_fee', 19, 8)->default(0)->unsigned();
            $table->string('txn_id')->nullable();
            $table->string('api')->nullable();
            $table->string('receipt', 100)->nullable();
            $table->string('status', 20)->default(STATUS_PENDING)->index();
            $table->timestamps();

            $table->index(['user_id', 'symbol']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('wallet_id')
                ->references('id')
                ->on('wallets')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('symbol')
                ->references('symbol')
                ->on('coins')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('bank_account_id')
                ->references('id')
                ->on('bank_accounts')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('system_bank_account_id')
                ->references('id')
                ->on('bank_accounts')
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
        Schema::dropIfExists('wallet_deposits');
    }
}
