<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        DB::table('users')->insert([
            ['name' => 'Pimpinan', 'email' => 'pimpinan@example.com', 'password' => Hash::make('password'), 'role' => 0],
            ['name' => 'Petugas', 'email' => 'petugas1@example.com', 'password' => Hash::make('password'), 'role' => 1],
            ['name' => 'Petugas2', 'email' => 'petugas2@example.com', 'password' => Hash::make('password'), 'role' => 3],
            ['name' => 'User', 'email' => 'user@example.com', 'password' => Hash::make('password'), 'role' => 2],
        ]);
    }

}
