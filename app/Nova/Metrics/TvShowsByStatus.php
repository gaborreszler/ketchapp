<?php

namespace App\Nova\Metrics;

use App\TvShow;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class TvShowsByStatus extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, TvShow::class, 'status')
					->label(function($status) {
						return TvShow::STATUS_RELATIONS[$status];
					})
					->colors([
						'#6cb2eb',
						'Planned' => '#6cb2eb',
						'In Production' => '#6cb2eb',
						'Pilot' => '#6cb2eb',
						'Returning Series' => '#38c172',
						'Canceled' => '#e3342f',
						'Ended' => '#e3342f',
					]);
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'tv-shows-by-status';
    }

	/**
	 * Get the displayable name of the metric.
	 *
	 * @return string
	 */
	public function name()
	{
		return 'TV shows by status';
	}
}
