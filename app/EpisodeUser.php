<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EpisodeUser extends Model
{
	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function seasonUser()
	{
		return $this->belongsTo('App\SeasonUser');
    }

	public function episode()
	{
		return $this->belongsTo('App\Episode');
    }
}
