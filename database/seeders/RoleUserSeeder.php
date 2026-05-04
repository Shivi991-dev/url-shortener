<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RoleUserSeeder extends Seeder
{   
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['super admin', 'admin', 'member'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $users = [
            ['email' => 'superadmin@gmail.com', 'name' => 'SuperAdmin', 'password' => Hash::make('password')],
        ];

        foreach ($users as $user) {
            $userModel = User::updateOrCreate(['email' => $user['email']], $user);
            $userModel->assignRole('super admin');
        }
    }
}
