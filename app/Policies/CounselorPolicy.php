<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Counselor;
use Illuminate\Auth\Access\Response;

class CounselorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Admin $admin, Counselor $counselor): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $admin): bool
    {
        return $admin->role->value == 'super';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $admin, Counselor $counselor): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $admin, Counselor $counselor): bool
    {
        return $admin->role->value == 'super';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Admin $admin, Counselor $counselor): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Admin $admin, Counselor $counselor): bool
    {
        return true;
    }
}
