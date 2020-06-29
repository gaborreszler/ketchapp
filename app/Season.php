<?php

namespace App;

use App\Libraries\Tmdb;
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

	public function users()
	{
		return $this->belongsToMany('App\User', 'season_users')
					->withPivot('following');
    }

	public function seasonUsers()
	{
		return $this->hasMany('App\SeasonUser');
    }

	public function createSeasonUsers()
	{
		foreach ($this->tvShow->tvShowUsers as $tvShowUser) {
			$seasonUser = new SeasonUser();
			$seasonUser->user_id = $tvShowUser->user_id;
			$seasonUser->season_id = $this->id;
			$seasonUser->tv_show_user_id = $tvShowUser->id;
			$seasonUser->save();
		}
    }

	public function getTmdbSeason()
	{
		$tmdb = new Tmdb(config('app.TMDb_key'));
		$result = $tmdb->request([
			"tv" => $this->tvShow->tmdb_identifier,
			"season" => $this->season_number
		]);

		return $result;
    }
}
