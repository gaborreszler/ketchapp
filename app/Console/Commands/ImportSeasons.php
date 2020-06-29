<?php

namespace App\Console\Commands;

use App\Jobs\ImportSeason;
use App\TvShow;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ImportSeasons extends Command
{
	use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'season:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Imports seasons from TMDb which do not exist in the database.";

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
    	foreach (TvShow::all() as $tvShow)
			foreach ($tvShow->getTmdbTvShow()->seasons as $tmdbSeason)
				$this->dispatchNow(new ImportSeason($tmdbSeason, $tvShow));
    	exit;
    }
}
