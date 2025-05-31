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
            ['email' => 'adminSuper@x.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('katasandi'), // ganti dengan password aman
            ]
        );
    }
}
