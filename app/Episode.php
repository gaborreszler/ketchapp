<?php

namespace App;

use App\Libraries\Tmdb;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
	public function season()
	{
		return $this->belongsTo('App\Season');
    }

	public function users()
	{
		return $this->belongsToMany('App\User', 'episode_users')
					->withPivot('seen');
    }

	public function episodeUsers()
	{
		return $this->hasMany('App\EpisodeUser');
    }

	public function episodeImages()
	{
		return $this->hasMany('App\EpisodeImage');
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

	public function createEpisodeUsers()
	{
		foreach ($this->season->seasonUsers as $seasonUser) {
			$episodeUser = new EpisodeUser();
			$episodeUser->user_id = $seasonUser->user_id;
			$episodeUser->episode_id = $this->id;
			$episodeUser->season_user_id = $seasonUser->id;
			$episodeUser->save();
		}
    }

	public function getTmdbEpisode()
	{
		$tmdb = new Tmdb(config('app.TMDb_key'));
		$result = $tmdb->request([
			"tv" => $this->season->tvShow->tmdb_identifier,
			"season" => $this->season->season_number,
			"episode" => $this->episode_number
		]);

		return $result;
    }
}
