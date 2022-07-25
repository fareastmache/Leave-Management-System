<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'create-users']);
        Permission::create(['name' => 'edit-users']);
        Permission::create(['name' => 'delete-users']);

        Permission::create(['name' => 'add-leaves']);
        Permission::create(['name' => 'edit-leaves']);
        Permission::create(['name' => 'delete-leaves']);
        Permission::create(['name' => 'apply-leaves']);

        Permission::create(['name' => 'approve-applications']);
        Permission::create(['name' => 'reject-applications']);
        Permission::create(['name' => 'edit-application']);
        Permission::create(['name' => 'cancel-application']);

        $adminRole = Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Supervisor']);
        Role::create(['name' => 'Employee']);

        $adminRole->givePermissionTo([
            'create-users',
            'edit-users',
            'delete-users',
            'add-leaves',
            'edit-leaves',
            'delete-leaves',
            'apply-leaves',
            'approve-applications',
            'reject-applications',
            'edit-application',
            'cancel-application',
        ]);
    }
}
