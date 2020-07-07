<?php

namespace App;

use App\Libraries\Tmdb;
use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['tmdb_identifier', 'name'];

	public function tvShows()
	{
		return $this->belongsToMany('App\TvShow', 'network_tv_shows');
    }

	public function networkImages()
	{
		return $this->hasMany('App\NetworkImage');
    }

	public function getImage()
	{
		if($image = $this->networkImages()->where('svg', true)->first())
			return $image->getFilePublicPath(true);
		elseif($image = $this->networkImages()->first())
			return $image->getFilePublicPath();
    }

	public function createNetworkImages($size = NetworkImage::SIZE_ORIGINAL)
	{
		foreach ($this->getTmdbNetworkImages()->logos as $tmdbNetworkImage) {
			$networkImage = new NetworkImage();
			$networkImage->network_id = $this->id;
			$networkImage->size = $size;
			$networkImage->file_path = $tmdbNetworkImage->file_path;
			$networkImage->svg = $svg = $tmdbNetworkImage->file_type === ".svg" ? true : false;
			$networkImage->save();

			$networkImage->storeFile($size);
			if ($svg) $networkImage->storeFile($size, true);
		}
    }

	public function getTmdbNetworkImages()
	{
		$tmdb = new Tmdb(config('app.TMDb_key'));
		$result = $tmdb->request([
			"network" => $this->tmdb_identifier,
			"images" => null
		]);

		return $result;
    }
}
