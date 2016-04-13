<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiConsumersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_consumers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('api_token')->unique();
            $table->integer('level')->default(0);
            $table->string('reset_key')->nullable();
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
        Schema::drop('api_consumers');
    }
}
