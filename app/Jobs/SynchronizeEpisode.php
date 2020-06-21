<?php

namespace App\Jobs;

use App\Episode;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SynchronizeEpisode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public $tries = 3;
    protected $episode;

    /**
     * Create a new job instance.
     *
	 * @param Episode $episode
     * @return void
     */
    public function __construct(Episode $episode)
    {
        $this->episode = $episode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
    	$mismatch = false;
		$tmdbEpisode = $this->episode->getTmdbEpisode();

		if ($this->episode->title !== $tmdbEpisode->name) {
			$mismatch = true;
			$this->episode->title = $tmdbEpisode->name;
		}

		if (!empty($tmdbEpisode->air_date) && $this->episode->air_date != $tmdbEpisode->air_date) {
			$mismatch = true;
			$this->episode->air_date = $tmdbEpisode->air_date;
		}

		if ($mismatch) $this->episode->update();

		if ($tmdbEpisode->still_path !== null) {
			$swap_primary = null;
			if (count($this->episode->episodeImages) > 0) {
				$old_primary = $this->episode->episodeImages()->where('primary', 1)->first();
				$swap_primary = $old_primary && $old_primary->file_path !== $tmdbEpisode->still_path ? $old_primary : null;
			}

			if (count($this->episode->episodeImages) === 0 || $swap_primary)
				$this->episode->createEpisodeImage($tmdbEpisode->still_path, true, $swap_primary);
		}
    }
}
