<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;

class EpisodeUser extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\EpisodeUser::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

	/**
	 * Indicates if the resource should be displayed in the sidebar.
	 *
	 * @var bool
	 */
	public static $displayInNavigation = false;

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

			BelongsTo::make('User')
				->sortable()
				->hideWhenUpdating(),

			BelongsTo::make('Season User', 'seasonUser')
				->sortable()
				->hideWhenUpdating(),

			BelongsTo::make('Episode')
				->sortable()
				->hideWhenUpdating(),

			Boolean::make('Seen')->sortable(),

			DateTime::make('Created at')
				->sortable()
				->hideWhenUpdating(),

			DateTime::make('Updated at')
				->sortable()
				->hideWhenUpdating(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

	/**
	 * Get the value that should be displayed to represent the resource.
	 *
	 * @return string
	 */
	public function title()
	{
		return "{$this->user->name} - {$this->episode->season->tvShow->title} {$this->episode->seasonNumberEpisodeNumber()} [#{$this->id}]";
	}
}
