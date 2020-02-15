<?php

namespace App\Console\Commands;

use App\Episode;
use Illuminate\Console\Command;

class ImportEpisodes extends Command
{
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
    	Episode::import();
    	exit;
    }
}
