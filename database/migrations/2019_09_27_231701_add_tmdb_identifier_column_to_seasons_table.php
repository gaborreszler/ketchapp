<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTmdbIdentifierColumnToSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seasons', function (Blueprint $table) {
            $table->unsignedMediumInteger('tmdb_identifier')->after('tv_show_id');

			$table->unique('tmdb_identifier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seasons', function (Blueprint $table) {
			$table->dropUnique('seasons_tmdb_identifier_unique');

			$table->dropColumn('tmdb_identifier');
        });
    }
}
