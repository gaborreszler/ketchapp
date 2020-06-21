<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeasonUser extends Model
{
	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function tvShowUser()
	{
		return $this->belongsTo('App\TvShowUser');
    }

	public function season()
	{
		return $this->belongsTo('App\Season');
    }

	public function episodeUsers()
	{
		return $this->hasMany('App\EpisodeUser');
    }
}
