<?php

namespace App\Console\Commands;

use App\Mail\DatabaseBackupDumped;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DumpDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:dump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Creates a backup of the database.";

    protected $filename, $process;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->filename = strtolower(config('app.name')) . '_' . date('Y_m_d_His') . '.sql';

        $this->process = new Process(sprintf(
			'mysqldump -u%s -p%s %s %s > %s',
			config('database.connections.mysql.username'),
			config('database.connections.mysql.password'),
			config('database.connections.mysql.database'),
			"--ignore-table=".config('database.connections.mysql.database').".failed_jobs",
			storage_path('app/' . $this->filename)
		));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
        	$this->process->mustRun();

			$this->info('Successfully created backup: ' . $this->filename);

			Mail::to([[
				"email" => config('app.mail.address'),
				"name" => config('app.name')
			]])->send(new DatabaseBackupDumped($this->filename));

		} catch (ProcessFailedException $exception) {
			$this->error('Failed to create backup: ' . $this->filename . PHP_EOL . $exception);
		}
		exit;
    }
}
