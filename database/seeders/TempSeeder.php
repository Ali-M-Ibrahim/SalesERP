<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TempSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $salesRep = User::updateOrCreate(
            [
                'email' => 'sales2@saleserp.com',
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
