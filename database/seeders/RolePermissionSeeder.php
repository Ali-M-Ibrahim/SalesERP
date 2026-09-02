<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Clear cached roles and permissions.
         */
        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();

        /*
         * Permissions
         */
        $permissions = [
            'customers.view',
            'customers.create',
            'customers.update',
            'visits.view',
            'visits.create',
            'visits.checkin',
            'visits.checkout',
            'samples.view',
            'samples.give',
            'resources.view',
            'resources.create',
            'resources.update',
            'resources.share',
            'notes.create',
            'team.view',
            'reports.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        /*
         * Admin
         */
        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $admin->syncPermissions([
            'customers.view',
            'visits.view',
            'samples.view',
            'resources.view',
            'resources.create',
            'resources.update',
            'notes.create',
            'team.view',
            'reports.view',
        ]);


        /*
         * Sales Representative
         */
        $salesRep = Role::firstOrCreate([
            'name' => 'sales_rep',
            'guard_name' => 'web',
        ]);

        $salesRep->syncPermissions([
            'customers.view',
            'customers.create',
            'customers.update',
            'resources.create',
            'visits.view',
            'visits.create',
            'visits.checkin',
            'visits.checkout',
            'resources.share',
            'samples.view',
            'samples.give',
            'resources.view',
        ]);
    }
}
