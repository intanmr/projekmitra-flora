<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin.mitraflora@gmail.com'],
            [
                'name' => 'Admin Mitra Flora',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );
    }
}