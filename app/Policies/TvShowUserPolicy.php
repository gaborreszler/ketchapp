<?php

namespace App\Policies;

use App\TvShowUser;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TvShowUserPolicy
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
     * @param  \App\TvShowUser  $tvShowUser
     * @return mixed
     */
    public function view(User $user, TvShowUser $tvShowUser)
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
     * @param  \App\TvShowUser  $tvShowUser
     * @return mixed
     */
    public function update(User $user, TvShowUser $tvShowUser)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\TvShowUser  $tvShowUser
     * @return mixed
     */
    public function delete(User $user, TvShowUser $tvShowUser)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\TvShowUser  $tvShowUser
     * @return mixed
     */
    public function restore(User $user, TvShowUser $tvShowUser)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\TvShowUser  $tvShowUser
     * @return mixed
     */
    public function forceDelete(User $user, TvShowUser $tvShowUser)
    {
        return false;
    }
}
