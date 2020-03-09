<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetworkImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_images', function (Blueprint $table) {
            $table->smallIncrements('id');
			$table->unsignedTinyInteger('network_id');
			$table->boolean('svg')->nullable(false)->default(false);
			$table->string('size')->nullable(false);
			$table->string('file_path')->nullable(false);
            $table->timestamps();

			$table->foreign('network_id')->references('id')->on('networks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('network_images', function (Blueprint $table) {
			$table->dropForeign('network_images_network_id_foreign');
		});
        Schema::dropIfExists('network_images');
    }
}
