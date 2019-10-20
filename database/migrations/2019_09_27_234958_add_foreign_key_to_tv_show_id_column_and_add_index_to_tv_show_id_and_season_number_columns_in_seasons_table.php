<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToTvShowIdColumnAndAddIndexToTvShowIdAndSeasonNumberColumnsInSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seasons', function (Blueprint $table) {
			$table->foreign('tv_show_id')->references('id')->on('tv_shows')->onDelete('restrict');
			$table->unique(['tv_show_id', 'season_number']);
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
			$table->dropForeign('seasons_tv_show_id_foreign');
			$table->dropUnique('seasons_tv_show_id_season_number_unique');
        });
    }
}
