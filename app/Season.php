<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
	public function tvShow()
	{
		return $this->belongsTo('App\TvShow');
    }

	public function episodes()
	{
		return $this->hasMany('App\Episode');
    }

	public function tvShowUserSeasons()
	{
		return $this->hasMany('App\TvShowUserSeason');
    }

	public static function import(TvShow $tvShow = null)
	{
		if ($tvShow) {
			$tvShows = new Collection();
			$tvShows[] = $tvShow;
		} else {
			$tvShows = TvShow::all();
		}

		//$number_of_new_seasons = 0;
		//dump(date('Y-m-d H:i:s'));
		foreach ($tvShows as $tvShow) {
			foreach ($tvShow->getTmdbTvShow()->seasons as $tmdbSeason) {
				$tmdbSeasonId = $tmdbSeason->id;
				$tmdbSeasonNumber = $tmdbSeason->season_number;

				$doesntExist = Season::where('tv_show_id', $tvShow->id)->where(function ($query) use ($tmdbSeasonId, $tmdbSeasonNumber) {
					$query->where('tmdb_identifier', $tmdbSeasonId)
						  ->orWhere('season_number', $tmdbSeasonNumber);
				})->doesntExist();

				if ($doesntExist) {
					$season = new Season();
					$season->tv_show_id = $tvShow->id;
					$season->tmdb_identifier = $tmdbSeason->id;
					$season->season_number = $tmdbSeason->season_number;
					$season->save();

					//dump($season->tvShow->title . " S" . str_pad($tmdbSeasonNumber, 2, "0", STR_PAD_LEFT));

					//$number_of_new_seasons++;

					$season->createTvShowUserSeasons();
				}
			}
		}
		//dump(date('Y-m-d H:i:s'));

		//dump("Imported " . $number_of_new_seasons . " new seasons.");
		return;
    }

	public function createTvShowUserSeasons()
	{
		foreach ($this->tvShow->tvShowUsers as $tvShowUser) {
			$tvShowUserSeason = new TvShowUserSeason();
			$tvShowUserSeason->tv_show_user_id = $tvShowUser->id;
			$tvShowUserSeason->season_id = $this->id;
			$tvShowUserSeason->save();
		}
    }

	public function getTmdbSeason()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.themoviedb.org/3/tv/" . $this->tvShow->tmdb_identifier . "/season/" . $this->season_number . "?language=en-US&api_key=" . config('app.TMDb_key'),
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
