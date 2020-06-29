<?php

namespace App\Jobs;

use App\Network;
use App\TvShow;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SynchronizeTvShow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public $tries = 3;
    protected $tvShow;

    /**
     * Create a new job instance.
     *
	 * @param TvShow $tvShow
     * @return void
     */
    public function __construct(TvShow $tvShow)
    {
        $this->tvShow = $tvShow;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
    	$tmdbTvShow = $this->tvShow->getTmdbTvShow();

		$network_ids = [];
		foreach ($tmdbTvShow->networks as $tmdbNetwork) {
			$network = Network::firstOrCreate(
				['tmdb_identifier' => $tmdbNetwork->id],
				['name' => $tmdbNetwork->name]
			);
			$network_ids[] = $network->id;

			if (count($network->networkImages) === 0)
				$network->createNetworkImages();
		}
		$this->tvShow->networks()->sync($network_ids, false);

		$this->handleTvShowImage($tmdbTvShow->poster_path, 'portrait');
		$this->handleTvShowImage($tmdbTvShow->backdrop_path, 'landscape');

		$tmdbTvShowStatus = $this->tvShow->correlateStatus($tmdbTvShow->status);
		if ($this->tvShow->status !== $tmdbTvShowStatus) {
			$this->tvShow->status = $tmdbTvShowStatus;
			$this->tvShow->update();
		}
    }

	public function handleTvShowImage($file_path, $view)
	{
		if ($file_path !== null) {
			$old_primary = null;
			$swap_primary = null;
			if ($this->tvShow->tvShowImages()->where('view', $view)->count() > 0) {
				$old_primary = $this->tvShow->tvShowImages()->where(['primary' => 1, 'view' => $view])->first();
				$swap_primary = $old_primary && $old_primary->file_path !== $file_path ? $old_primary : null;
			}

			if (!$old_primary || $swap_primary)
				$this->tvShow->createTvShowImage($file_path, $view, true, $swap_primary);
		}
    }
}
