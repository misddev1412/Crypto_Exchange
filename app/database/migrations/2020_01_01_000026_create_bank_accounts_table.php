<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable()->index();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('bank_name')->index();
            $table->string('iban');
            $table->string('swift');
            $table->string('reference_number')->nullable();
            $table->string('account_holder')->nullable();
            $table->string('bank_address')->nullable();
            $table->string('account_holder_address')->nullable();
            $table->integer('is_verified')->default(INACTIVE);
            $table->integer('is_active')->default(ACTIVE);
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
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
        Schema::dropIfExists('bank_accounts');
    }
}
