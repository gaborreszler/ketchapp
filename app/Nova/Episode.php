<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Episode extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Episode::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
		'id', 'title'
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

			Avatar::make('Image', function() {
				return $this->getImage();
			})->squared(),

			Text::make('TV show', function() {
				return "<a class='no-underline dim text-primary font-bold' href='/nova/resources/tv-shows/{$this->season->tvShow->{$this->season->tvShow->getRouteKeyName()}}'>{$this->season->tvShow->title} [#{$this->season->tvShow->id}]</a>";
			})->asHtml(),

			BelongsTo::make('Season')->sortable(),

			Number::make('Episode', 'episode_number')->sortable(),

			Text::make('Title')
				->sortable()
				->rules('required', 'max:255'),

			Date::make('Air date')->sortable(),

			Text::make('TMDb', function() {
				return "<a class='no-underline dim text-primary font-bold' href='https://www.themoviedb.org/tv/{$this->season->tvShow->tmdb_identifier}/season/{$this->season->season_number}/episode/{$this->episode_number}' target='_blank'>{$this->tmdb_identifier}</a>";
			})->asHtml(),

			DateTime::make('Created at')->sortable(),

			DateTime::make('Updated at')->sortable(),

			HasMany::make('Episode images', 'episodeImages')
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
		return "{$this->seasonNumberEpisodeNumber()} ({$this->title}) [#{$this->id}]";
	}

	/**
	 * Get the search result subtitle for the resource.
	 *
	 * @return string|null
	 */
	public function subtitle()
	{
		return "{$this->season->tvShow->title}";
	}
}
