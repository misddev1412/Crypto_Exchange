<?php

// use DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIcoStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ico_stages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->bigInteger('total_tokens');
            $table->double('base_price');
            $table->integer('min_purchase')->default(0);
            $table->integer('max_purchase')->default(0);
            $table->bigInteger('soft_cap')->default(0);
            $table->bigInteger('hard_cap')->default(0);
            $table->string('display_mode');
            $table->integer('private')->default(0);
            $table->integer('user_panel_display')->default(0);
            $table->double('sales_token')->default(0);
            $table->double('sales_amount')->default(0);
            $table->string('status')->default('active');
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
        Schema::dropIfExists('ico_stages');
    }
}
