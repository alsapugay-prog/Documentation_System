<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Dito natin ilalagay ang paggawa ng iyong personal login credentials para hindi nabubura
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@example.com'], // Hahanapin kung may umiiral na email
            [
                'name' => 'Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            ]
        );
    }
}