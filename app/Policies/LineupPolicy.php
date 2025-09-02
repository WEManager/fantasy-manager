<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Lineup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class LineupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any lineups.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the lineup.
     *
     * @return mixed
     */
    public function view(User $user, Lineup $lineup)
    {
        return optional($user->club)->id === $lineup->club_id;
    }

    /**
     * Determine whether the user can create lineups.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the lineup.
     *
     * @return mixed
     */
    public function update(User $user, Lineup $lineup)
    {
        return optional($user->club)->id === $lineup->club_id;
    }

    /**
     * Determine whether the user can delete the lineup.
     *
     * @return mixed
     */
    public function delete(User $user, Lineup $lineup)
    {
        //
    }

    /**
     * Determine whether the user can restore the lineup.
     *
     * @return mixed
     */
    public function restore(User $user, Lineup $lineup)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the lineup.
     *
     * @return mixed
     */
    public function forceDelete(User $user, Lineup $lineup)
    {
        //
    }
}
