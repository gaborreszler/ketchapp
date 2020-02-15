<?php

namespace App;

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
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.themoviedb.org/3/tv/" . $this->tmdb_identifier . "?language=en-US&api_key=" . config('app.TMDb_key'),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_POSTFIELDS => "{}",
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		}

		return json_decode($response);
    }
}
