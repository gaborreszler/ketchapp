<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TvShowImage extends Model
{
	const SIZE_ORIGINAL = 'original';

	const VIEW_PORTRAIT = 'portrait';
	const VIEW_LANDSCAPE = 'landscape';

	protected static $base_url = 'https://image.tmdb.org/t/p';

	protected $fillable = ['tv_show_id', 'primary', 'view', 'size', 'file_path'];

	public function tvShow()
	{
		return $this->belongsTo('App\TvShow');
    }

	public function getExternalUrl($size = self::SIZE_ORIGINAL)
	{
		return self::$base_url . DIRECTORY_SEPARATOR . $size . $this->file_path;
	}

	public function getFilePublicPath()
	{
		$path = 'tv-show-images' . DIRECTORY_SEPARATOR . $this->tvShow->tmdb_identifier . DIRECTORY_SEPARATOR . $this->view . DIRECTORY_SEPARATOR . $this->size;
		return $path . $this->file_path;
	}

	public function storeFile($view, $size = self::SIZE_ORIGINAL)
	{
		$path = 'tv-show-images' . DIRECTORY_SEPARATOR . $this->tvShow->tmdb_identifier . DIRECTORY_SEPARATOR . $view . DIRECTORY_SEPARATOR . $size;

		$public_disk = Storage::disk('public');
		$file = $path . $this->file_path;
		if (!$public_disk->exists($file)) {
			$public_disk->put($file, file_get_contents($this->getExternalUrl($size)));
		}
	}
}
