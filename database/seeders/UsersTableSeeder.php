<?php

namespace Database\Seeders;

use App\Models\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name'       => 'Admin User',
                'email'      => 'admin@admin.com',
                'password'   => Hash::make('password'),
                'roles'      => ['admin'],
            ],
            [
                'name'      => 'Regular User',
                'email'     => 'user@user.com',
                'password'  => Hash::make('password'),
                'roles'     => ['subscriber'],
            ],
        ];

        foreach ($users as $userData) {
            $userRoles = $userData['roles'];
            unset($userData['roles']);
            $user =  User::updateOrCreate(['email' => $userData['email']],$userData);
            
            foreach ($userRoles as $role) {
                $role = Roles::where('name', $role)->first();
                if ($role) {
                    $user->roles()->attach($role->id);
                }
            }
        }
    }
}
