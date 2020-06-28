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
		'App\Console\Commands\SendReminders',
		'App\Console\Commands\SynchronizeEpisodes',
		'App\Console\Commands\SynchronizeTvShows',
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
				 ->sundays()
				 ->at('23:59');

		$schedule->command('tv-show:synchronize')
				 ->dailyAt('12:00');
        $schedule->command('episode:synchronize')
				 ->dailyAt('12:05');

        $schedule->command('season:import')
				 ->dailyAt('00:00');
		$schedule->command('episode:import')
				 ->dailyAt('00:05');

        $schedule->command('reminder:send daily')
				 ->dailyAt('18:00');
		$schedule->command('reminder:send weekly')
				 ->sundays()
				 ->at('20:00');
		$schedule->command('reminder:send monthly')
				 ->monthlyOn(date('t'), '22:00');

		$schedule->command('horizon:snapshot')->everyFifteenMinutes();
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
