<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class TvShow extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\TvShow::class;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
		'id', 'title', 'imdb_identifier'
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

			Avatar::make('Portrait image', function() {
				return $this->getPortraitImage();
			})->squared(),

			Avatar::make('Landscape image', function() {
				return $this->getLandscapeImage();
			})->squared(),

			Text::make('Title')
				->sortable()
				->rules('required', 'max:255'),

			Badge::make('Status', function() {
				return $this->getStatus();
			})->map(array_combine(\App\TvShow::STATUS_RELATIONS, array_replace(\App\TvShow::STATUS_AND_BOOTSTRAP_COLOR_RELATIONS, [0 => 'info', 1 => 'info', 3 => 'info']))),

			Text::make('IMDB', function() {
				$imdb_identifier = $this->imdb_identifier;
				return "<a class='no-underline dim text-primary font-bold' href='https://www.imdb.com/title/{$imdb_identifier}' target='_blank'>{$imdb_identifier}</a>";
			})->asHtml(),

			Text::make('TMDb', function() {
				$tmdb_identifier = $this->tmdb_identifier;
				return "<a class='no-underline dim text-primary font-bold' href='https://www.themoviedb.org/tv/{$tmdb_identifier}' target='_blank'>{$tmdb_identifier}</a>";
			})->asHtml(),

			DateTime::make('Created at')->sortable(),

			DateTime::make('Updated at')->sortable(),

			BelongsToMany::make('Networks'),

			HasMany::make('Seasons'),

			HasMany::make('TV show images', 'tvShowImages')
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
	 * Get the displayable label of the resource.
	 *
	 * @return string
	 */
	public static function label()
	{
		return 'TV shows';
	}

	/**
	 * Get the value that should be displayed to represent the resource.
	 *
	 * @return string
	 */
	public function title()
	{
		return "{$this->title} [#{$this->id}]";
	}
}
