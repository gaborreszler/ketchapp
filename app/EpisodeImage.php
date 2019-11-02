<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EpisodeImage extends Model
{
	public function episode()
	{
		return $this->belongsTo('App\Episode');
    }
}
