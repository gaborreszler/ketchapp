<?php

namespace App;

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
}
