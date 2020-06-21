<?php

namespace App\Console\Commands;

use App\Jobs\SynchronizeTvShow;
use App\TvShow;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SynchronizeTvShows extends Command
{
	use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tv-show:synchronize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronizes TV show statuses, networks and primary TV show images.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	foreach (TvShow::all() as $tvShow) {
    		$job = new SynchronizeTvShow($tvShow);
    		$this->dispatch($job);
		}
    	exit;
    }
}
