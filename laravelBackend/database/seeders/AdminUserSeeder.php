<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'ADMIN',
            'name' => 'ADMIN',
            'email' => 'test3@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('test'),
            'role_id' => 1,
        ]);
    }
}
