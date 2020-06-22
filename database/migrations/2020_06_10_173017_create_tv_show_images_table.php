<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTvShowImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tv_show_images', function (Blueprint $table) {
            $table->smallIncrements('id');
			$table->unsignedSmallInteger('tv_show_id');
			$table->boolean('primary')->nullable(false)->default(true);
			$table->string('view')->nullable(false);
			$table->string('size')->nullable(false);
			$table->string('file_path');
            $table->timestamps();

            $table->unique(['tv_show_id', 'view', 'size', 'file_path']);

            $table->foreign('tv_show_id')->references('id')->on('tv_shows')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tv_show_images');
    }
}
