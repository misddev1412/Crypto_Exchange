<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->uuid('id')->primary();
            $table->string('assigned_role', 20);
            $table->uuid('referrer_id')->nullable();
            $table->string('referral_code')->nullable();
            $table->string('username', 20)->unique();
            $table->string('email', 50)->unique();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->string('google2fa_secret', 30)->nullable();
            $table->boolean('is_id_verified')->default(UNVERIFIED);
            $table->boolean('is_email_verified')->default(UNVERIFIED);
            $table->boolean('is_financial_active')->default(ACTIVE);
            $table->boolean('is_accessible_under_maintenance')->default(INACTIVE);
            $table->boolean('is_super_admin')->default(INACTIVE);
            $table->string('status', 20)->default(STATUS_ACTIVE);
            $table->rememberToken();
            $table->uuid('created_by')->nullable();
            $table->timestamps();

            $table->foreign('assigned_role')
                ->references('slug')
                ->on('roles')
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
        Schema::drop('users');
    }
}
