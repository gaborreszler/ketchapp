<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTmdbIdentifierColumnToEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('episodes', function (Blueprint $table) {
            $table->unsignedMediumInteger('tmdb_identifier')->after('season_id');

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
        Schema::table('episodes', function (Blueprint $table) {
			$table->dropUnique('episodes_tmdb_identifier_unique');

			$table->dropColumn('tmdb_identifier');
		});
    }
}
