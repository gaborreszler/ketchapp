<?php

namespace App\Console\Commands;

use App\Episode;
use App\Jobs\SynchronizeEpisode;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SynchronizeEpisodes extends Command
{
	use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'episode:synchronize {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronizes episode titles, air dates and primary episode images.';

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
    	$episodes = $this->option('all')	? Episode::all()
												: Episode::where('updated_at', '>=', date('Y-m-d', strtotime('-3 months')))->get();

    	foreach ($episodes as $episode) {
    		$job = new SynchronizeEpisode($episode);
    		$this->dispatch($job);
		}
    	exit;
    }
}
