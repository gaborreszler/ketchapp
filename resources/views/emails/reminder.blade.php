@component('mail::message')
# Dear {{ $user->name }},
here's your {{ $interval }} reminder about your TV shows' episodes aired {{ $interval_text }} and you haven't seen them (yet):

@foreach($episodes as $tv_show_title => $value)
- {{ $tv_show_title }}
@foreach ($value as $episode)
	- S{{ str_pad($episode->season->season_number, 2, "0", STR_PAD_LEFT) }}E{{ str_pad($episode->episode_number, 2, "0", STR_PAD_LEFT) }} {{ $episode->title }}
@endforeach
@endforeach

Have fun,<br>
{{ config('app.name') }}
@endcomponent