<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemindedDailyAndRemindedWeeklyAndRemindedMonthlyColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
			$table->boolean('reminded_monthly')->nullable(false)->default(false)->after('remember_token');
			$table->boolean('reminded_weekly')->nullable(false)->default(false)->after('remember_token');
			$table->boolean('reminded_daily')->nullable(false)->default(false)->after('remember_token');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('reminded_daily');
			$table->dropColumn('reminded_weekly');
			$table->dropColumn('reminded_monthly');
		});
    }
}
