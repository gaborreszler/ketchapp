<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TvShowUserSeasonEpisode extends Model
{
	public function tvShowUserSeason()
	{
		return $this->belongsTo('App\TvShowUserSeason');
    }

	public function episode()
	{
		return $this->belongsTo('App\Episode');
    }
}
