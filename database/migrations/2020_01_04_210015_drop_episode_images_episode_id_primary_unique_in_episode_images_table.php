<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropEpisodeImagesEpisodeIdPrimaryUniqueInEpisodeImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('episode_images', function (Blueprint $table) {
            $table->dropUnique('episode_images_episode_id_primary_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('episode_images', function (Blueprint $table) {
            $table->unique(['episode_id', 'primary']);
        });
    }
}
