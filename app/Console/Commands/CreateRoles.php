<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use App\Models\Admin;
use App\Models\Student;

class CreateRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'risman:create-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       /* $adminRole = Role::create(['name' => 'super_admin']);
        $schoolRole = Role::create(['name' => 'school']);
        $supervisorRole = Role::create(['name' => 'supervisor']);
	Admin::find(1)->assignRole('super_admin');*/

	    $students = Student::with('user')->whereRelation('counselor.user','school_id',2)->get();
	    foreach($students as $student )$student->user()->update(['school_id' => 2]);

    }
}
