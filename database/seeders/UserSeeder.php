<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Admin
         */
        $admin = User::updateOrCreate(
            [
                'email' => 'admin@erp.com',
            ],
            [
                'name' => 'System Admin',
                'phone' => '03000000',
                'password' => Hash::make('123'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles(['admin']);

        /*
         * Sales Representative
         */
        $salesRep = User::updateOrCreate(
            [
                'email' => 'sales@erp.com',
            ],
            [
                'name' => 'Sales Representative',
                'phone' => '03111111',
                'password' => Hash::make('123'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $salesRep->syncRoles(['sales_rep']);
    }
}
