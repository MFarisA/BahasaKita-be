<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@x.com'], // adjust as needed
            [
                'name' => 'Admin1',
                'password' => Hash::make('katasandi'), // use a strong password
                'email_verified_at' => now(),
            ]
        );
    }
}
