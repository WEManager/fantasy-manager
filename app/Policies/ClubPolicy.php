<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Club;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClubPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any clubs.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the club.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Club  $club
     * @return mixed
     */
    public function view(User $user, Club $club)
    {
        //
    }

    /**
     * Determine whether the user can create clubs.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the club.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Club  $club
     * @return mixed
     */
    public function update(User $user, Club $club)
    {
        return optional($user->club)->id === $club->id;
    }

    /**
     * Determine whether the user can delete the club.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Club  $club
     * @return mixed
     */
    public function delete(User $user, Club $club)
    {
        //
    }

    /**
     * Determine whether the user can restore the club.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Club  $club
     * @return mixed
     */
    public function restore(User $user, Club $club)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the club.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Club  $club
     * @return mixed
     */
    public function forceDelete(User $user, Club $club)
    {
        //
    }
}
