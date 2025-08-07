<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create job',
            'edit job',
            'delete job',
            'view job',
            'view report'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
        }

        $role = Role::create(['name' => 'admin', 'guard_name' => 'api']);
        $role->givePermissionTo(['create job', 'edit job', 'delete job', 'view job', 'view report']);

        $role = Role::create(['name' => 'kepala kompetensi', 'guard_name' => 'api']);
        $role->givePermissionTo(['create job', 'edit job', 'delete job', 'view job', 'view report']);

        $role = Role::create(['name' => 'guru', 'guard_name' => 'api']);
        $role->givePermissionTo(['create job', 'edit job', 'delete job', 'view job', 'view report']);

        $role = Role::create(['name' => 'murid', 'guard_name' => 'api']);
        $role->givePermissionTo(['view job']);
    }
}
