<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TvShow extends Model
{
	public function seasons()
	{
		return $this->hasMany('App\Season');
    }

	public function users()
	{
		return $this->belongsToMany('App\User', 'tv_show_users')
					->withPivot('watching');
    }

	public function tvShowUsers()
	{
		return $this->hasMany('App\TvShowUser');
    }
}
