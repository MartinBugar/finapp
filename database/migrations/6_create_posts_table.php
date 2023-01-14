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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userID');
            $table->unsignedBigInteger('value');
            $table->string('name');
            $table->unsignedBigInteger('typeID');
            $table->longText('description');
            $table->date('date');
            $table->timestamps();
            $table->string('pdf')->nullable();
            $table->string('pdfName')->nullable();
            $table->foreign('userID')->references('id')->on('users');
            $table->foreign('typeID')->references('id')->on('expenses_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
