<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Season extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Season::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

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

			BelongsTo::make('TV show', 'tvShow')->sortable(),

			Number::make('Season', 'season_number')->sortable(),

			Text::make('TMDb', function() {
				return "<a class='no-underline dim text-primary font-bold' href='https://www.themoviedb.org/tv/{$this->tvShow->tmdb_identifier}/season/{$this->season_number}' target='_blank'>{$this->tmdb_identifier}</a>";
			})->asHtml(),

			DateTime::make('Created at')->sortable(),

			DateTime::make('Updated at')->sortable(),

			HasMany::make('Episodes'),
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
		return "{$this->seasonNumber()} [#{$this->id}]";
	}

	/**
	 * Get the search result subtitle for the resource.
	 *
	 * @return string|null
	 */
	public function subtitle()
	{
		return "{$this->tvShow->title}";
	}
}
