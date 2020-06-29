<?php

namespace App\Console\Commands;

use App\Jobs\ImportEpisode;
use App\Season;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ImportEpisodes extends Command
{
	use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'episode:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Imports episodes from TMDb which do not exist in Ketchapp's database.";

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
		foreach (Season::all() as $season)
			if (property_exists($tmdbSeason = $season->getTmdbSeason(), "episodes"))
				foreach ($tmdbSeason->episodes as $tmdbEpisode)
					$this->dispatchNow(new ImportEpisode($tmdbEpisode, $season));
    	exit;
    }
}
