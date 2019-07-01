<?php

namespace App\Policies;

use App\User;
use App\Lineup;
use Illuminate\Auth\Access\HandlesAuthorization;

class LineupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any lineups.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the lineup.
     *
     * @param  \App\User  $user
     * @param  \App\Lineup  $lineup
     * @return mixed
     */
    public function view(User $user, Lineup $lineup)
    {
        dd(['user' => $user->club, 'lineup' => $lineup]);
        return optional($user->club)->id === $lineup->club_id;
    }

    /**
     * Determine whether the user can create lineups.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the lineup.
     *
     * @param  \App\User  $user
     * @param  \App\Lineup  $lineup
     * @return mixed
     */
    public function update(User $user, Lineup $lineup)
    {
        return optional($user->club)->id === $lineup->club_id;
    }

    /**
     * Determine whether the user can delete the lineup.
     *
     * @param  \App\User  $user
     * @param  \App\Lineup  $lineup
     * @return mixed
     */
    public function delete(User $user, Lineup $lineup)
    {
        //
    }

    /**
     * Determine whether the user can restore the lineup.
     *
     * @param  \App\User  $user
     * @param  \App\Lineup  $lineup
     * @return mixed
     */
    public function restore(User $user, Lineup $lineup)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the lineup.
     *
     * @param  \App\User  $user
     * @param  \App\Lineup  $lineup
     * @return mixed
     */
    public function forceDelete(User $user, Lineup $lineup)
    {
        //
    }
}
