<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Daily;
use Illuminate\Auth\Access\Response;

class DailyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->hasRole('super_admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Admin $admin, Daily $daily): bool
    {
        return $admin->hasRole('super_admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $admin): bool
    {
        return $admin->hasRole('super_admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $admin, Daily $daily): bool
    {
        return $admin->hasRole('super_admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $admin, Daily $daily): bool
    {
        return $admin->hasRole('super_admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Admin $admin, Daily $daily): bool
    {
        return $admin->hasRole('super_admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Admin $admin, Daily $daily): bool
    {
        return $admin->hasRole('super_admin');
    }
}
