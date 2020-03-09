<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class NetworkImage extends Model
{
	protected static $base_url = 'https://image.tmdb.org/t/p';

	public function network()
	{
		return $this->belongsTo('App\Network');
    }

	public function getExternalUrl($size = "original", $svg = false)
	{
		return self::$base_url . DIRECTORY_SEPARATOR . $size . (!$svg ? $this->file_path : $this->replaceExtension($this->file_path, 'svg'));
	}

	public function getFilePublicPath($svg = false)
	{
		$path = 'network-images' . DIRECTORY_SEPARATOR . $this->network->tmdb_identifier . DIRECTORY_SEPARATOR . $this->size;
		return Storage::url($path.(!$svg?$this->file_path:$this->replaceExtension($this->file_path, 'svg')));
	}

	public function storeFile($size = "original", $svg = false)
	{
		$path = 'network-images'. DIRECTORY_SEPARATOR . $this->network->tmdb_identifier . DIRECTORY_SEPARATOR . $size;

		$public_disk = Storage::disk('public');
		$file = $path . (!$svg ? $this->file_path : $this->replaceExtension($this->file_path, 'svg'));
		if (!$public_disk->exists($file)) {
			$public_disk->put($file, file_get_contents($this->getExternalUrl($size, $svg)));
		}
	}

	public function replaceExtension($filename, $new_extension)
	{
		$info = pathinfo($filename);
		return $info['dirname'] . $info['filename'] . '.' . $new_extension;
	}
}
