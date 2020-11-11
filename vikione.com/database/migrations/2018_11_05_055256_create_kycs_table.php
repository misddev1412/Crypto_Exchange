<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKYCsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kycs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userId');
            $table->string('firstName');
            $table->string('lastName')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();
            $table->string('telegram')->default('')->nullable();
            $table->string('documentType')->default('')->nullable();
            $table->string('document')->default('')->nullable();
            $table->string('document2')->default('')->nullable();
            $table->string('document3')->default('')->nullable();
            $table->string('walletName')->default('')->nullable();
            $table->string('walletAddress')->default('')->nullable();
            $table->text('notes')->nullable();
            $table->integer('reviewedBy')->default(0);
            $table->datetime('reviewedAt')->nullable();
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('kycs');
    }
}
