<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'personnel_id' => 1000001,
            'name_first' => 'Administrator',
            'name_last' => 'User',
            'role' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456789'),
        ]);

        User::create([
            'personnel_id' => 1000002,
            'name_first' => 'Nurse',
            'name_last' => 'User',
            'role' => 'nurse',
            'email' => 'nurse@example.com',
            'password' => Hash::make('123456789'),
        ]);

        User::create([
            'personnel_id' => 1000003,
            'name_first' => 'Doctor',
            'name_last' => 'User',
            'role' => 'doctor',
            'email' => 'doctor@example.com',
            'password' => Hash::make('123456789'),
        ]);
    }
}
