<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'password' => Hash::make('PasswordS'),
            'role' => 'admin',
        ]);

        User::create([
            'username' => 'recep1',
            'password' => Hash::make('PasswordS'),
            'role' => 'receptionist',
        ]);

        User::create([
            'username' => 'recep2',
            'password' => Hash::make('PasswordS'),
            'role' => 'receptionist',
        ]);
    }
}
