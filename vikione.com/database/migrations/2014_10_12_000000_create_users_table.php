<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('status')->default('active');
            $table->string('registerMethod')->nullable()->default('Email');
            $table->string('social_id')->nullable();
            $table->string('mobile')->nullable();
            $table->string('dateOfBirth')->nullable();
            $table->string('nationality')->nullable();
            $table->dateTime('lastLogin');
            $table->string('walletType')->nullable();
            $table->string('walletAddress')->nullable();
            $table->enum('role', ['admin', 'manager', 'user'])->default('user');
            $table->double('contributed')->nullable();
            $table->double('tokenBalance')->nullable();
            $table->string('referral')->nullable();
            $table->text('referralInfo')->nullable();
            $table->integer('google2fa')->default(0);
            $table->text('google2fa_secret')->nullable();
            $table->enum('type', ['demo', 'main'])->default('main');

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
