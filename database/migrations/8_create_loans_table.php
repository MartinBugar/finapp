<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userID');
            $table->unsignedDouble('value');
            $table->string('name');
            $table->longText('description');
            $table->unsignedBigInteger('typeID')->nullable();
            $table->date('date');
            $table->timestamps();
            $table->foreign('userID')->references('id')->on('users')->onDelete('cascade');;
            $table->foreign('typeID')->references('id')->on('expenses_types')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
};
