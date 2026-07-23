<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin User
        $admin = User::updateOrCreate(
            ['email' => 'adminSuper@x.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('katasandi'),
            ]
        );

        Profile::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'xp' => 500,
                'photo' => null,
            ]
        );

        // 2. Demo Student Users (for Leaderboard & API testing)
        $users = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@bahasakita.com',
                'password' => Hash::make('password'),
                'xp' => 180,
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'siti.aminah@bahasakita.com',
                'password' => Hash::make('password'),
                'xp' => 140,
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@bahasakita.com',
                'password' => Hash::make('password'),
                'xp' => 90,
            ],
            [
                'name' => 'Agung Pratama',
                'email' => 'agung.pratama@bahasakita.com',
                'password' => Hash::make('password'),
                'xp' => 50,
            ],
            [
                'name' => 'Demo Learner',
                'email' => 'learner@bahasakita.com',
                'password' => Hash::make('password'),
                'xp' => 30,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                ]
            );

            Profile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'xp' => $userData['xp'],
                    'photo' => null,
                ]
            );
        }
    }
}
