<?php

namespace App\Policies;

use App\Models\TimeLog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TimeLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TimeLog $timeLog): bool
    {
        return $user->id === $timeLog->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TimeLog $timeLog): bool
    {
        return $user->id === $timeLog->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TimeLog $timeLog): bool
    {
        return $user->id === $timeLog->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TimeLog $timeLog): bool
    {
        return $user->id === $timeLog->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TimeLog $timeLog): bool
    {
        return $user->id === $timeLog->user_id;
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return false;
    }
}
