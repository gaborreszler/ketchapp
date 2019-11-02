<?php

namespace App\Console\Commands;

use App\Episode;
use Illuminate\Console\Command;

class SynchronizeEpisodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'episode:synchronize';

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
        Episode::synchronize();
        return;
    }
}
