<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguagesTable extends Migration
{
    private $tableName = 'languages';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->tableName)) return;

        Schema::create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->unique();
            $table->string('name')->unique();
            $table->string('short_code')->unique();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(ACTIVE);
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
        Schema::dropIfExists($this->tableName);
    }
}
