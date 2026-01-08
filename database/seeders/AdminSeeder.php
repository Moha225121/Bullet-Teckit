<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@bullet.ly'],
            [
                'name' => 'System Admin',
                'fan_id' => 'ADMIN-0001',
                'national_id' => '0000000000',
                'phone' => '0910000000',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );
    }
}
