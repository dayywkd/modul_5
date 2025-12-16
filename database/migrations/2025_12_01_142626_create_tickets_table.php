<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        $table->string('movie_title');
        $table->string('studio');
        $table->string('seat', 10);
        $table->dateTime('show_time');
        $table->integer('price');
        $table->string('user_name');
        $table->timestamps();
    });
}

};
