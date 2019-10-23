<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TvShowUserSeason extends Model
{
	public function tvShowUser()
	{
		return $this->belongsTo('App\TvShowUser');
    }

	public function season()
	{
		return $this->belongsTo('App\Season');
    }

	public function tvShowUserSeasonEpisodes()
	{
		return $this->hasMany('App\TvShowUserSeasonEpisode');
    }
}
