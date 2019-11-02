<?php

namespace App;

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
}
