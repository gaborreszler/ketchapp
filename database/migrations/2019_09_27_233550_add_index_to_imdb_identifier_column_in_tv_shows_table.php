<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToImdbIdentifierColumnInTvShowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tv_shows', function (Blueprint $table) {
			$table->unique('imdb_identifier');
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
			$table->dropUnique('tv_shows_imdb_identifier_unique');
        });
    }
}
