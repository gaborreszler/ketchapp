<?php

namespace App\Console\Commands;

use App\Season;
use Illuminate\Console\Command;

class ImportSeasons extends Command
{
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
    protected $description = "Imports seasons from TMDb which do not exist in Ketchapp's database.";

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
    	Season::import();
    	return;
    }
}
