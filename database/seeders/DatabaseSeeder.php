<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([

            'username' => 'fajri_chan',
            'name' => 'Fajri Rinaldi Chan',
            'email' => 'fajri@gariskode.com',
            'phone' => '089613390766',
            'role' => 'admin',
        ]);
    }
}
