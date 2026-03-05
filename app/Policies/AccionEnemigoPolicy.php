<?php

namespace App\Policies;

use App\Models\whquestacg\AccionEnemigo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AccionEnemigoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AccionEnemigo $accionEnemigo): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AccionEnemigo $accionEnemigo): bool
    {
        return $user->id === $accionEnemigo->user_id
            || $user->rol === 'coordinador';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AccionEnemigo $accionEnemigo): bool
    {
        return $user->id === $accionEnemigo->user_id
            || $user->rol === 'coordinador';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AccionEnemigo $accionEnemigo): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AccionEnemigo $accionEnemigo): bool
    {
        return false;
    }
}
