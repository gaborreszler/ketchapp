<?php

namespace App;

use App\Libraries\Tmdb;
use Illuminate\Database\Eloquent\Model;

class TvShow extends Model
{
	const STATUS_UNKNOWN		= 0;
	const STATUS_PLANNED		= 1;
	const STATUS_IN_PRODUCTION	= 2;
	const STATUS_PILOT			= 3;
	const STATUS_RETURNING		= 4;
	const STATUS_CANCELED		= 5;
	const STATUS_ENDED			= 6;

	const STATUS_RELATIONS = [
		self::STATUS_UNKNOWN => null,
		self::STATUS_PLANNED => 'Planned',
		self::STATUS_IN_PRODUCTION => 'In Production',
		self::STATUS_PILOT => 'Pilot',
		self::STATUS_RETURNING => 'Returning Series',
		self::STATUS_CANCELED => 'Canceled',
		self::STATUS_ENDED => 'Ended'
	];


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

	public static function getStatuses()
	{
		return self::STATUS_RELATIONS;
	}

	public function correlateStatus($status)
	{
		$statuses = $this->getStatuses();

		return array_search($status, $statuses) ?: self::STATUS_UNKNOWN;
	}

	public function getStatus()
	{
		return self::STATUS_RELATIONS[$this->status];
	}

	public function isReturning()
	{
		return $this->status === TvShow::STATUS_RETURNING;
	}

	public function getTmdbTvShow()
	{
		$tmdb = new Tmdb(config('app.TMDb_key'));
		$result = $tmdb->request([
			"tv" => $this->tmdb_identifier
		]);

		return $result;
    }

		return json_decode($response);
    }
}
