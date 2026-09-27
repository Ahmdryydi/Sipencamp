<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'username'     => 'admin',
                'name'         => 'Administrator',
                'email'        => 'admin@gmail.com',
                'password'     => Hash::make('123456'),
                'level'        => 'admin',
                'role'         => 'admin',
                'phone_number' => '081234567890',
            ],
            [
                'username'     => 'ihsan',
                'name'         => 'Muhammad Ihsan Mubarak',
                'email'        => 'user1@gmail.com',
                'password'     => Hash::make('3871'),
                'level'        => 'customer',
                'role'         => 'customer',
                'phone_number' => '089876543210',
            ],
            [
                'username'     => 'yadi',
                'name'         => 'Ahmad Riyadi',
                'email'        => 'user2@gmail.com',
                'password'     => Hash::make('3831'),
                'level'        => 'customer',
                'role'         => 'customer',
                'phone_number' => '089876543211',
            ],
            [
                'username'     => 'nanda',
                'name'         => 'Rizky Anandhita',
                'email'        => 'user3@gmail.com',
                'password'     => Hash::make('3843'),
                'level'        => 'customer',
                'role'         => 'customer',
                'phone_number' => '089876543212',
            ],
        ];

        foreach ($users as $user) {
            // Memeriksa berdasarkan username agar tidak duplikat
            User::updateOrCreate(
                ['username' => $user['username']],
                $user
            );
        }
    }
}
