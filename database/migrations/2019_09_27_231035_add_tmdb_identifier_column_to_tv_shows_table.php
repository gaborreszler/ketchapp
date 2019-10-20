<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTmdbIdentifierColumnToTvShowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tv_shows', function (Blueprint $table) {
			$table->unsignedMediumInteger('tmdb_identifier')->after('imdb_identifier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tv_shows', function (Blueprint $table) {
			$table->dropColumn('tmdb_identifier');
        });
    }
}
