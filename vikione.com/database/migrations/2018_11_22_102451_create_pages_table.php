<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('menu_title');
            $table->string('slug')->unique();
            $table->string('custom_slug');
            $table->string('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
            $table->integer('meta_index')->default(1);
            $table->longText('description');
            $table->string('external_link')->nullable();
            $table->string('status')->default('active');
            $table->tinyInteger('public')->default(0);
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
        Schema::dropIfExists('pages');
    }
}
