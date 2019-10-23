<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTvShowUserSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tv_show_user_seasons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tv_show_user_id');
            $table->unsignedMediumInteger('season_id');
            $table->boolean('following')->nullable(false)->default(true);
            $table->timestamps();

			$table->unique(['tv_show_user_id', 'season_id'], 'tv_show_user_seasons_tsu_id_season_id_unique');
			$table->foreign('tv_show_user_id')->references('id')->on('tv_show_users')->onDelete('restrict');
			$table->foreign('season_id')->references('id')->on('seasons')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tv_show_user_seasons');
    }
}
