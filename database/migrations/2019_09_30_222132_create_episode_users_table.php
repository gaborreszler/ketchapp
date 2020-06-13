<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpisodeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episode_users', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('user_id');
			$table->unsignedMediumInteger('episode_id');
			$table->unsignedBigInteger('season_user_id');
			$table->boolean('seen')->nullable(false)->default(false);
            $table->timestamps();

			$table->unique(['user_id', 'episode_id', 'season_user_id'], 'episode_users_user_id_episode_id_season_user_id_unique');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('episode_id')->references('id')->on('episodes')->onDelete('restrict');
			$table->foreign('season_user_id')->references('id')->on('season_users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('episode_users');
    }
}
