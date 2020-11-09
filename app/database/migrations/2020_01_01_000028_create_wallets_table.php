<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->string('symbol');
            $table->decimal('primary_balance', 19, 8)->unsigned()->default(0);
            $table->string('address')->nullable();
            $table->text('passphrase')->nullable();
            $table->integer('is_system_wallet')->default(INACTIVE);
            $table->integer('is_active')->default(ACTIVE);
            $table->timestamps();

            $table->index(['user_id', 'symbol']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('symbol')
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
        Schema::dropIfExists('wallets');
    }
}
