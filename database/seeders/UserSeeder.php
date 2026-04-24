<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pondok.test'],
            [
                'name' => 'Admin Pondok',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'musyrif@pondok.test'],
            [
                'name' => 'Musyrif Pondok',
                'password' => Hash::make('password123'),
                'role' => 'musyrif',
            ]
        );
    }
}