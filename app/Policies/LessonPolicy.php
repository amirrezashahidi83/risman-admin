<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Lesson;
use Illuminate\Auth\Access\Response;

class LessonPolicy
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
    public function view(Admin $admin, Lesson $lesson): bool
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
    public function update(Admin $admin, Lesson $lesson): bool
    {
        return $admin->hasRole('super_admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $admin, Lesson $lesson): bool
    {
        return $admin->hasRole('super_admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Admin $admin, Lesson $lesson): bool
    {
        return $admin->hasRole('super_admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Admin $admin, Lesson $lesson): bool
    {
        return $admin->hasRole('super_admin');
    }
}
