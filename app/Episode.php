<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
	public function season()
	{
		return $this->belongsTo('App\Season');
    }

	public function tvShowUserSeasonEpisodes()
	{
		return $this->hasMany('App\TvShowUserSeasonEpisode');
    }

	public function episodeImages()
	{
		return $this->hasMany('App\EpisodeImage');
    }

	public static function import(Season $season = null)
	{
		if ($season) {
			$seasons = new Collection();
			$seasons[] = $season;
		} else {
			$seasons = Season::all();
		}

		//$number_of_new_episodes = 0;
		//dump(date('Y-m-d H:i:s'));
		foreach ($seasons as $season) {
			foreach ($season->getTmdbSeason()->episodes as $tmdbEpisode) {
				$tmdbEpisodeId = $tmdbEpisode->id;
				$tmdbEpisodeNumber = $tmdbEpisode->episode_number;

				$doesntExist = Episode::where('season_id', $season->id)->where(function ($query) use ($tmdbEpisodeId, $tmdbEpisodeNumber) {
					$query->where('tmdb_identifier', $tmdbEpisodeId)
						  ->orWhere('episode_number', $tmdbEpisodeNumber);
				})->doesntExist();

				if ($doesntExist) {
					$episode = new Episode();
					$episode->season_id = $season->id;
					$episode->tmdb_identifier = $tmdbEpisode->id;
					$episode->episode_number = $tmdbEpisode->episode_number;
					$episode->title = $tmdbEpisode->name;
					$episode->air_date = $tmdbEpisode->air_date;
					$episode->save();

					//dump($season->tvShow->title . " S" . str_pad($tmdbEpisode->season_number, 2, "0", STR_PAD_LEFT) . "E" . str_pad($tmdbEpisode->episode_number, 2, "0", STR_PAD_LEFT));

					//$number_of_new_episodes++;

					if ($tmdbEpisode->still_path !== null)
						$episode->createEpisodeImage($tmdbEpisode->still_path, true);

					$episode->createTvShowUserSeasonEpisodes();
				}
			}
		}
		//dump(date('Y-m-d H:i:s'));

		//dump("Imported " . $number_of_new_episodes . " new episodes.");
		return;
    }

	public function createEpisodeImage($file_path, $primary = false)
	{
		$episodeImage = new EpisodeImage();
		$episodeImage->episode_id = $this->id;
		$episodeImage->primary = $primary ? true : false;
		$episodeImage->file_path = $file_path;
		$episodeImage->save();
    }

	public function createTvShowUserSeasonEpisodes()
	{
		foreach ($this->season->tvShowUserSeasons as $tvShowUserSeason) {
			$tvShowUserSeasonEpisode = new TvShowUserSeasonEpisode();
			$tvShowUserSeasonEpisode->tv_show_user_season_id = $tvShowUserSeason->id;
			$tvShowUserSeasonEpisode->episode_id = $this->id;
			$tvShowUserSeasonEpisode->save();
		}
    }
}
