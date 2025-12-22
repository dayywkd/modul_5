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
        if (Schema::hasTable('tickets')) {
            if (!Schema::hasColumn('tickets', 'description')) {
                Schema::table('tickets', function (Blueprint $table) {
                    $table->text('description')->after('movie_title');
                });
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('tickets') && Schema::hasColumn('tickets', 'description')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }

};
