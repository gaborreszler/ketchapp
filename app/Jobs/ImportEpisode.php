<?php

namespace App\Jobs;

use App\Episode;
use App\Season;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportEpisode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tmdbEpisode;
    protected $season;

	/**
	 * Create a new job instance.
	 *
	 * @param mixed $tmdbEpisode
	 * @param Season $season
	 */
    public function __construct($tmdbEpisode, Season $season)
    {
        $this->tmdbEpisode = $tmdbEpisode;
        $this->season = $season;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		$tmdbEpisodeId = $this->tmdbEpisode->id;
		$tmdbEpisodeNumber = $this->tmdbEpisode->episode_number;

		$doesntExist = Episode::where('season_id', $this->season->id)->where(function ($query) use ($tmdbEpisodeId, $tmdbEpisodeNumber) {
			$query->where('tmdb_identifier', $tmdbEpisodeId)
				->orWhere('episode_number', $tmdbEpisodeNumber);
		})->doesntExist();

		if ($doesntExist) {
			$episode = new Episode();
			$episode->season_id = $this->season->id;
			$episode->tmdb_identifier = $this->tmdbEpisode->id;
			$episode->episode_number = $this->tmdbEpisode->episode_number;
			$episode->title = $this->tmdbEpisode->name;
			$episode->air_date = $this->tmdbEpisode->air_date;
			$episode->save();

			if ($this->tmdbEpisode->still_path !== null)
				$episode->createEpisodeImage($this->tmdbEpisode->still_path, true);

			$episode->createEpisodeUsers();
		}
    }
}
