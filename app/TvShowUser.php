<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TvShowUser extends Model
{
	public function user()
	{
		return $this->belongsTo('App\User');
    }

	public function tvShow()
	{
		return $this->belongsTo('App\TvShow');
    }
}
