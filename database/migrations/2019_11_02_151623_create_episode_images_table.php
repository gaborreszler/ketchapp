<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEpisodeImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('episode_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedMediumInteger('episode_id');
            $table->boolean('primary')->nullable(false)->default(false);
            $table->string('file_path')->nullable(false);
            $table->timestamps();

			$table->unique(['episode_id', 'file_path']);
			$table->unique(['episode_id', 'primary']);
            $table->foreign('episode_id')->references('id')->on('episodes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('episode_images');
    }
}
