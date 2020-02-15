<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSizeColumnToEpisodeImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('episode_images', function (Blueprint $table) {
            $table->string('size')->nullable(false)->after('primary');

			$table->unique(['episode_id', 'size', 'file_path']);
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
        	$table->dropUnique('episode_images_episode_id_size_file_path_unique');
            $table->dropColumn('size');
        });
    }
}
