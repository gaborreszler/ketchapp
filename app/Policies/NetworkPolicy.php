<?php

namespace App\Policies;

use App\Network;
use App\TvShow;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NetworkPolicy
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
     * @param  \App\Network  $network
     * @return mixed
     */
    public function view(User $user, Network $network)
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
     * @param  \App\Network  $network
     * @return mixed
     */
    public function update(User $user, Network $network)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Network  $network
     * @return mixed
     */
    public function delete(User $user, Network $network)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Network  $network
     * @return mixed
     */
    public function restore(User $user, Network $network)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Network  $network
     * @return mixed
     */
    public function forceDelete(User $user, Network $network)
    {
        return false;
    }

	/**
	 * Determine whether the user can attach a TV show to a network.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Network  $network
	 * @param  \App\TvShow  $tvShow
	 * @return mixed
	 */
    public function attachTvShow(User $user, Network $network, TvShow $tvShow)
	{
		return false;
	}

	/**
	 * Determine whether the user can detach a TV show from a network.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Network  $network
	 * @param  \App\TvShow  $tvShow
	 * @return mixed
	 */
	public function detachTvShow(User $user, Network $network, TvShow $tvShow)
	{
		return false;
	}

	/**
	 * Determine whether the user can attach any TV shows to the network.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Network  $network
	 * @return mixed
	 */
	public function attachAnyTvShow(User $user, Network $network)
	{
		return false;
    }

	/**
	 * Determine whether the user can detach any TV shows from the network.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Network  $network
	 * @return mixed
	 */
    public function detachAnyTvShow(User $user, Network $network)
	{
		return false;
	}
}
