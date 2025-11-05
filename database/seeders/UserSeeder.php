<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin with specified credentials
        User::firstOrCreate(
            ['email' => 'a@a.com'],
            [
                'name' => 'Admin User',
                'email' => 'a@a.com',
                'password' => Hash::make('11112222'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Create 10 regular users
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike.johnson@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ],
            [
                'name' => 'Sarah Williams',
                'email' => 'sarah.williams@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ],
            [
                'name' => 'David Brown',
                'email' => 'david.brown@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily.davis@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ],
            [
                'name' => 'Chris Wilson',
                'email' => 'chris.wilson@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ],
            [
                'name' => 'Lisa Anderson',
                'email' => 'lisa.anderson@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ],
            [
                'name' => 'Tom Martinez',
                'email' => 'tom.martinez@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ],
            [
                'name' => 'Amy Taylor',
                'email' => 'amy.taylor@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}

