<?php

namespace Database\Seeders;

use App\Models\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name'         => 'admin',
                'display_name' => 'Admin',
                'description'  => 'System administrator with full access',
                'status'       => 1,
            ],
            [
                'name'         => 'subscriber',
                'display_name' => 'Subscriber',
                'description'  => 'subscriber with full access',
                'status'       => 1,
            ],
            [
                'name'         => 'user',
                'display_name' => 'User',
                'description'  => 'Standard user with basic access',
                'status'       => 1,
            ],
        ];

        foreach ($roles as $role) {
            Roles::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
