<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_metas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userId');
            $table->integer('notify_admin')->default(0);
            $table->integer('newsletter')->default(1);
            $table->integer('unusual')->default(1);
            $table->string('save_activity')->default('TRUE');
            $table->string('pwd_chng')->default('TRUE');
            $table->string('pwd_temp')->nullable();
            $table->dateTime('email_expire')->nullable();
            $table->string('email_token', 220)->nullable();
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
        Schema::dropIfExists('user_metas');
    }
}
