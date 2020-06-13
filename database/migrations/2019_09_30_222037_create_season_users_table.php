<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeasonUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('season_users', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('user_id');
			$table->unsignedMediumInteger('season_id');
			$table->unsignedBigInteger('tv_show_user_id');
            $table->boolean('following')->nullable(false)->default(true);
            $table->timestamps();

			$table->unique(['user_id', 'season_id', 'tv_show_user_id'], 'season_users_user_id_season_id_tv_show_user_id_unique');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('season_id')->references('id')->on('seasons')->onDelete('restrict');
			$table->foreign('tv_show_user_id')->references('id')->on('tv_show_users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('season_users');
    }
}
