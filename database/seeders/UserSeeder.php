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
            'name' => "Admin Digitup",
            'email' => "admin@digitup.dz",
            "email_verified_at" =>now(),
            'password' => Hash::make("test@admin123"),
            'role' => config('constant.USERS_ROLE.ADMIN'),
        ]);

        User::create([
            'name' => "user Digitup",
            'email' => "user1@digitup.dz",
            "email_verified_at" =>now(),
            'password' => Hash::make("test@user123"),
            'role' => config('constant.USERS_ROLE.USER'),
        ]);
    }
}
