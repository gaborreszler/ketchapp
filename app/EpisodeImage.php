<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EpisodeImage extends Model
{
	const SIZE_ORIGINAL = 'original';

	protected static $base_url = 'https://image.tmdb.org/t/p';

	public function episode()
	{
		return $this->belongsTo('App\Episode');
    }

	public function getExternalUrl($size = self::SIZE_ORIGINAL)
	{
		return self::$base_url . DIRECTORY_SEPARATOR . $size . $this->file_path;
    }

	public function getFilePublicPath()
	{
		$path = 'episode-images' . DIRECTORY_SEPARATOR . $this->episode->season->tvShow->tmdb_identifier . DIRECTORY_SEPARATOR . $this->episode->season->tmdb_identifier . DIRECTORY_SEPARATOR . $this->episode->tmdb_identifier . DIRECTORY_SEPARATOR . $this->size;
		return $path . $this->file_path;
    }

	public function storeFile($size = self::SIZE_ORIGINAL)
	{
		$path = 'episode-images' . DIRECTORY_SEPARATOR . $this->episode->season->tvShow->tmdb_identifier . DIRECTORY_SEPARATOR . $this->episode->season->tmdb_identifier . DIRECTORY_SEPARATOR . $this->episode->tmdb_identifier . DIRECTORY_SEPARATOR . $size;

		$public_disk = Storage::disk('public');
		$file = $path . $this->file_path;
		if (!$public_disk->exists($file)) {
			$public_disk->put($file, file_get_contents($this->getExternalUrl($size)));
		}
    }

	public static function getExternalImageMimeType($url)
	{
		return get_headers($url, 1)["Content-Type"];
    }
}
