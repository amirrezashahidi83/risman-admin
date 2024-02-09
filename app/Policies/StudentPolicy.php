<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Student;
use Illuminate\Auth\Access\Response;

class StudentPolicy
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
    public function view(Admin $admin, Student $student): bool
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
    public function update(Admin $admin, Student $student): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $admin, Student $student): bool
    {
        return $admin->role->value == 'super';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Admin $admin, Student $student): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Admin $admin, Student $student): bool
    {
        return true;
    }
}
