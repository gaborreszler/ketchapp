<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TvShowImage extends Model
{
	protected static $base_url = 'https://image.tmdb.org/t/p';

	public function tvShow()
	{
		return $this->belongsTo('App\TvShow');
    }

	public function getExternalUrl($size = "original")
	{
		return self::$base_url . DIRECTORY_SEPARATOR . $size . $this->file_path;
	}

	public function getFilePublicPath($view)
	{
		$path = 'tv-show-images' . DIRECTORY_SEPARATOR . $this->tvShow->tmdb_identifier . DIRECTORY_SEPARATOR . $view . DIRECTORY_SEPARATOR . $this->size;
		return Storage::url($path.$this->file_path);
	}

	public function storeFile($view, $size = "original")
	{
		$path = 'tv-show-images' . DIRECTORY_SEPARATOR . $this->tvShow->tmdb_identifier . DIRECTORY_SEPARATOR . $view . DIRECTORY_SEPARATOR . $size;

		$public_disk = Storage::disk('public');
		$file = $path . $this->file_path;
		if (!$public_disk->exists($file)) {
			$public_disk->put($file, file_get_contents($this->getExternalUrl($size)));
		}
	}
}
