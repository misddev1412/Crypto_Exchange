<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tnx_id');
            $table->string('tnx_type');
            $table->dateTime('tnx_time');
            $table->double('tokens')->default(0);
            $table->double('bonus_on_base')->default(0);
            $table->double('bonus_on_token')->default(0);
            $table->double('total_bonus')->default(0);
            $table->double('total_tokens');
            $table->integer('stage');
            $table->integer('user');
            $table->double('amount')->nullable();
            $table->double('receive_amount')->default(0); // From Payment Controller
            $table->string('receive_currency')->nullable(); // From Payment Controller
            $table->double('base_amount')->nullable(); // Payment Method Base amount
            $table->string('base_currency')->nullable(); // Payment Method Base currency
            $table->double('base_currency_rate')->nullable(); // Payment Method Base currency
            $table->string('currency')->nullable(); // From Payment Controller
            $table->double('currency_rate')->nullable(); // Our Manual Currency Rate
            $table->text('all_currency_rate')->nullable(); // Our Manual Currency Rate
            $table->string('wallet_address')->nullable(); // From Payment Controller
            $table->string('payment_method')->nullable(); // Selected Method
            $table->string('payment_id')->default(''); // From Payment Controller
            $table->string('payment_to')->nullable(); // To our address
            $table->text('checked_by')->nullable();
            $table->text('added_by')->nullable();
            $table->dateTime('checked_time')->nullable();
            $table->string('details')->default('');
            $table->text('extra')->nullable();
            $table->string('status')->default('');
            $table->integer('dist')->default(0);
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
        Schema::dropIfExists('transactions');
    }
}
