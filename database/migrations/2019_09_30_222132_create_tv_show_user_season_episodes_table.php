<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTvShowUserSeasonEpisodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tv_show_user_season_episodes', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('tv_show_user_season_id');
			$table->unsignedMediumInteger('episode_id');
			$table->boolean('seen')->nullable(false)->default(false);
            $table->timestamps();

			$table->unique(['tv_show_user_season_id', 'episode_id'], 'tv_show_user_season_episodes_tsus_id_episode_id_unique');
			$table->foreign('tv_show_user_season_id')->references('id')->on('tv_show_user_seasons')->onDelete('restrict');
			$table->foreign('episode_id')->references('id')->on('episodes')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tv_show_user_season_episodes');
    }
}
