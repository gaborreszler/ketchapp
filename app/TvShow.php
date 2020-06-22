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

	const STATUS_AND_BOOTSTRAP_COLOR_RELATIONS = [
		self::STATUS_UNKNOWN => 'secondary',
		self::STATUS_PLANNED => 'secondary',
		self::STATUS_IN_PRODUCTION => 'info',
		self::STATUS_PILOT => 'primary',
		self::STATUS_RETURNING => 'success',
		self::STATUS_CANCELED => 'danger',
		self::STATUS_ENDED => 'danger'
	];

	public function tvShowImages()
	{
		return $this->hasMany('App\TvShowImage');
	}

	public function networks()
	{
		return $this->belongsToMany('App\Network', 'network_tv_shows');
	}

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

	public function createTvShowImage($file_path, $view, $primary = false, $swap_primary = null, $size = "original")
	{
		if ($primary && $swap_primary) {
			$swap_primary->primary = 0;
			$swap_primary->update();
		}

		$tvShowImage = TvShowImage::updateOrCreate(
			['tv_show_id' => $this->id, 'view' => $view, 'size' => $size, 'file_path' => $file_path],
			['primary' => $primary ? true : false]
		);

		$tvShowImage->storeFile($view);
	}

	public function getStatusColor()
	{
		return self::STATUS_AND_BOOTSTRAP_COLOR_RELATIONS[$this->status];
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
}
