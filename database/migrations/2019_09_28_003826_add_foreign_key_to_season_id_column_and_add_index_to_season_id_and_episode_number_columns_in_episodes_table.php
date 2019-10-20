<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToSeasonIdColumnAndAddIndexToSeasonIdAndEpisodeNumberColumnsInEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('episodes', function (Blueprint $table) {
			$table->foreign('season_id')->references('id')->on('seasons')->onDelete('restrict');
			$table->unique(['season_id', 'episode_number']);
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
			$table->dropForeign('episodes_season_id_foreign');
			$table->dropUnique('episodes_season_id_episode_number_unique');
        });
    }
}
