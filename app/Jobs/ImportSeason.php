<?php

namespace App\Jobs;

use App\Season;
use App\TvShow;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportSeason implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tmdbSeason;
    protected $tvShow;
    protected $season;

	/**
	 * Create a new job instance.
	 *
	 * @param mixed $tmdbSeason
	 * @param TvShow $tvShow
	 */
    public function __construct($tmdbSeason, TvShow $tvShow)
    {
        $this->tmdbSeason = $tmdbSeason;
        $this->tvShow = $tvShow;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		$tmdbSeasonId = $this->tmdbSeason->id;
		$tmdbSeasonNumber = $this->tmdbSeason->season_number;

		$doesntExist = Season::where('tv_show_id', $this->tvShow->id)->where(function ($query) use ($tmdbSeasonId, $tmdbSeasonNumber) {
			$query->where('tmdb_identifier', $tmdbSeasonId)
				->orWhere('season_number', $tmdbSeasonNumber);
		})->doesntExist();

		if ($doesntExist) {
			$season = new Season();
			$season->tv_show_id = $this->tvShow->id;
			$season->tmdb_identifier = $this->tmdbSeason->id;
			$season->season_number = $this->tmdbSeason->season_number;
			$season->save();

			$this->season = $season;

			$season->createSeasonUsers();
		}
    }

	/**
	 * @return Season
	 */
	public function getSeason()
	{
		return $this->season;
    }
}
