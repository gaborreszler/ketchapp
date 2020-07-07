<?php

namespace App\Policies;

use App\Network;
use App\TvShow;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TvShowPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\TvShow  $tvShow
     * @return mixed
     */
    public function view(User $user, TvShow $tvShow)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\TvShow  $tvShow
     * @return mixed
     */
    public function update(User $user, TvShow $tvShow)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\TvShow  $tvShow
     * @return mixed
     */
    public function delete(User $user, TvShow $tvShow)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\TvShow  $tvShow
     * @return mixed
     */
    public function restore(User $user, TvShow $tvShow)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\TvShow  $tvShow
     * @return mixed
     */
    public function forceDelete(User $user, TvShow $tvShow)
    {
        return false;
    }

	/**
	 * Determine whether the user can attach a network to a TV show.
	 *
	 * @param  \App\User  $user
	 * @param  \App\TvShow  $tvShow
	 * @param  \App\Network  $network
	 * @return mixed
	 */
	public function attachNetwork(User $user, TvShow $tvShow, Network $network)
	{
		return false;
	}

	/**
	 * Determine whether the user can detach a network from a TV show.
	 *
	 * @param  \App\User  $user
	 * @param  \App\TvShow  $tvShow
	 * @param  \App\Network  $network
	 * @return mixed
	 */
	public function detachNetwork(User $user, TvShow $tvShow, Network $network)
	{
		return false;
	}

    /**
	 * Determine whether the user can attach any TV shows to the network.
	 *
	 * @param  \App\User  $user
	 * @param  \App\TvShow  $tvShow
	 * @return mixed
	 */
	public function attachAnyNetwork(User $user, TvShow $tvShow)
	{
		return false;
	}

	/**
	 * Determine whether the user can detach any TV shows from the network.
	 *
	 * @param  \App\User  $user
	 * @param  \App\TvShow  $tvShow
	 * @return mixed
	 */
	public function detachAnyNetwork(User $user, TvShow $tvShow)
	{
		return false;
	}
}
