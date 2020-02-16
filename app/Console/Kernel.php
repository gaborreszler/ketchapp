<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\DumpDatabase',
		'App\Console\Commands\ImportEpisodes',
		'App\Console\Commands\ImportSeasons',
		'App\Console\Commands\SynchronizeEpisodes',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('db:dump')
				 ->mondays()
				 ->at('02:59');

        $schedule->command('episode:synchronize')
				 ->dailyAt('01:30');

        $schedule->command('season:import')
				 ->dailyAt('03:00');
		$schedule->command('episode:import')
				 ->dailyAt('03:05');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
