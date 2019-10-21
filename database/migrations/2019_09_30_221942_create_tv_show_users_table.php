<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTvShowUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tv_show_users', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('user_id');
			$table->unsignedSmallInteger('tv_show_id');
			$table->boolean('watching')->nullable(false)->default(true);
            $table->timestamps();

			$table->unique(['user_id', 'tv_show_id'], 'tv_show_users_user_id_ts_id_unique');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
			$table->foreign('tv_show_id')->references('id')->on('tv_shows')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tv_show_users');
    }
}
