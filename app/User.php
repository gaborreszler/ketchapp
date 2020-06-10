<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'reminded_daily', 'reminded_weekly', 'reminded_monthly',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

	public function tvShows()
	{
		return $this->belongsToMany('App\TvShow', 'tv_show_users')
					->withPivot('watching');
    }

	public function tvShowUsers()
	{
		return $this->hasMany('App\TvShowUser');
    }

	public function shouldBeReminded($interval)
	{
		if ($interval == "daily" && $this->reminded_daily)		return true;
		if ($interval == "weekly" && $this->reminded_weekly)	return true;
		if ($interval == "monthly" && $this->reminded_monthly)	return true;

		return false;
    }
}
