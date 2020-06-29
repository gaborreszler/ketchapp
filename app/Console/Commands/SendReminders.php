<?php

namespace App\Console\Commands;

use App\Episode;
use App\Mail\EpisodesAired;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:send {interval}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Sends a reminder e-mail to users about their TV shows' episodes aired today/this week/this month.";

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
    	$interval = $this->argument('interval');
    	$statement = null;
    	$today = date('Y-m-d');
    	$interval_date = null;
    	$interval_texts = [
    		"daily" => "today",
			"weekly" => "this week",
			"monthly" => "this month"
		];

    	if ($interval === "daily") {

			$statement = Episode::where('air_date', $today);

			$interval_date = $today;

		} else if ($interval === "weekly") {

			$interval_start = date('Y-m-d', strtotime('-6 days'));

    		$statement = Episode::where([
    			['air_date', '>=', $interval_start],
				['air_date', '<=', $today]
			])->orderBy('air_date');

			$interval_date = $interval_start . " - " . $today;

		} else if ($interval === "monthly") {

			$interval_start = date('Y-m-01');

    		$statement = Episode::where([
				['air_date', '>=', $interval_start],
				['air_date', '<=', $today]
			])->orderBy('air_date');

    		$interval_date = $interval_start . " - " . $today;
		}
    	$episodes = $statement->get();

    	$array = [];
    	foreach ($episodes as $episode)
    		foreach ($episode->episodeUsers as $episodeUser)
    			if (
					$episodeUser->seasonUser->tvShowUser->user->shouldBeReminded($interval)
					&&
    				!$episodeUser->seen
					&&
					$episodeUser->seasonUser->following
					&&
					($tvShowUser = $episodeUser->seasonUser->tvShowUser)->watching
				)
					$array[$tvShowUser->user_id][$tvShowUser->tv_show_id][] = $episode->id;

		//$number_of_emails_sent = 0;
    	foreach ($array as $user_id => $value) {
			$user = User::find($user_id);

			$episodes_to_be_reminded_about = [];
			foreach ($value as $tv_show_id => $episode_ids)
				foreach ($episode_ids as $episode_id) {
					$episode = Episode::find($episode_id);

					$episodes_to_be_reminded_about[$episode->season->tvShow->title][] = $episode;
				}

			Mail::to([[
				"email" => $user->email,
				"name" => $user->name
			]])->send(new EpisodesAired($user, $episodes_to_be_reminded_about, $interval, $interval_date, $interval_texts[$interval]));
			//todo: add reminder email sending to queue
			//$number_of_emails_sent++;
		}

    	//dump("Sent out " . $number_of_emails_sent . " " . $interval . " e-mails.");
    	exit;
    }
}
