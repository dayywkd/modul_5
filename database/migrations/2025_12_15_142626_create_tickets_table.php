<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            // Relasi dan Identifier Unik (Wajib UAP)
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('slug')->unique();


            // Data Tiket/Film
            $table->string('movie_title');
            $table->text('description')->nullable();
            $table->string('studio')->nullable();
            $table->string('seat', 10)->nullable();
            $table->dateTime('show_time')->nullable();
            $table->integer('price')->nullable(); // Cukup tulis satu kali
            $table->string('user_name')->nullable();

            // Data Event (Jika memang dibutuhkan dalam satu tabel)
            $table->string('event_name')->nullable();
            $table->text('event_description')->nullable();

            // Metadata (Cukup tulis satu kali)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
