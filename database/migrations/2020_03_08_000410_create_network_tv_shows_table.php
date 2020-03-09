<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNetworkTvShowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('network_tv_shows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('network_id');
            $table->unsignedSmallInteger('tv_show_id');

			$table->foreign('network_id')->references('id')->on('networks')->onDelete('restrict');
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
    	Schema::table('network_tv_shows', function (Blueprint $table) {
    		$table->dropForeign('network_tv_shows_network_id_foreign');
    		$table->dropForeign('network_tv_shows_tv_show_id_foreign');
    	});
        Schema::dropIfExists('network_tv_shows');
    }
}
