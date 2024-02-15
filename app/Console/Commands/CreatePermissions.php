<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreatePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    private function adminPermissions(){

    }

    private function schoolPermissions(){

    }

    private function supervisorPermissions(){

    }

    public function handle()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $schoolRole = Role::create(['name' => 'school']);
        $supervisorRole = Role::create(['name' => 'supervisor']);

        $excel = Permission::create(['name' => 'add_excel']);

        $add_student = Permission::create(['name' => 'add_student']);
        $add_counselor = Permission::create(['name' => 'add_counselor']);
        $add_admin = Permission::create(['name' => 'add_admin']);

        $edit_student = Permission::create(['name' => 'edit_student']);
        $edit_counselor = Permission::create(['name' => 'edit_counselor']);
        $edit_admin = Permission::create(['name' => 'edit_admin']);

        $delete_student = Permission::create(['name' => 'delete_student']);
        $delete_counselor = Permission::create(['name' => 'delete_counselor']);
        $delete_admin = Permission::create(['name' => 'delete_admin']);

        $show_students = Permission::create(['name' => 'show_students']);
        $show_counselors = Permission::create(['name' => 'show_counselors']);
        $show_admins = Permission::create(['name' => 'show_admins']);

        $edit_student_essentials = Permission::create(['name' => 'edit_student_essentials']);
        $edit_counselor_essentials = Permission::create(['name' => 'edit_counselor_essentials']);


    }
}
