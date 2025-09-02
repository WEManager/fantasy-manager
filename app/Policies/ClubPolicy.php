<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Club;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class ClubPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any clubs.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the club.
     *
     * @return mixed
     */
    public function view(User $user, Club $club)
    {
        //
    }

    /**
     * Determine whether the user can create clubs.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the club.
     *
     * @return mixed
     */
    public function update(User $user, Club $club)
    {
        return optional($user->club)->id === $club->id;
    }

    /**
     * Determine whether the user can delete the club.
     *
     * @return mixed
     */
    public function delete(User $user, Club $club)
    {
        //
    }

    /**
     * Determine whether the user can restore the club.
     *
     * @return mixed
     */
    public function restore(User $user, Club $club)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the club.
     *
     * @return mixed
     */
    public function forceDelete(User $user, Club $club)
    {
        //
    }
}
