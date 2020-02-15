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

	public function createEpisodeImage($file_path, $primary = false, $swap_primary = null, $size = "original")
	{
		$episodeImage = new EpisodeImage();
		$episodeImage->episode_id = $this->id;
		$episodeImage->primary = $primary ? true : false;

		if ($primary && $swap_primary) {
			$swap_primary->primary = 0;
			$swap_primary->update();
		}

		$episodeImage->size = $size;
		$episodeImage->file_path = $file_path;
		$episodeImage->save();

		$episodeImage->storeFile();
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

	public static function synchronize()
	{
		$episodes = Episode::all();

		//dump(date('Y-m-d H:i:s'));
		foreach ($episodes as $episode) {
			$tmdbEpisode = $episode->getTmdbEpisode();

			if (!isset($tmdbEpisode->status_code) || $tmdbEpisode->status_code !== 34) {
				$mismatch = false;

				if ($episode->title !== $tmdbEpisode->name) {
					$mismatch = true;
					$episode->title = $tmdbEpisode->name;
				}
				if ($episode->air_date != $tmdbEpisode->air_date) {
					$mismatch = true;
					$episode->air_date = $tmdbEpisode->air_date;
				}

				if ($mismatch)
					$episode->save();

				if (count($episode->episodeImages) === 0 && $tmdbEpisode->still_path !== null)
					$episode->createEpisodeImage($tmdbEpisode->still_path, true);
			}
		}
		//dump(date('Y-m-d H:i:s'));
    }

	public function getTmdbEpisode()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.themoviedb.org/3/tv/" . $this->season->tvShow->tmdb_identifier . "/season/" . $this->season->season_number . "/episode/" . $this->episode_number . "?language=en-US&api_key=" . config('app.TMDb_key'),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_POSTFIELDS => "{}",
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		}

		return json_decode($response);
    }
}
