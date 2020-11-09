<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralEarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_earnings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('referrer_user_id');
            $table->uuid('referral_user_id');
            $table->string('symbol', 10);
            $table->decimal('amount', 19, 8)->unsigned();
            $table->timestamps();

            $table->index(['referrer_user_id', 'symbol']);
            $table->index(['referral_user_id', 'symbol']);

            $table->foreign('symbol')
                ->references('symbol')
                ->on('coins')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('referrer_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('referral_user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('referral_earnings');
    }
}
