<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OrganizerSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'organizer@bullet.ly'],
            [
                'name' => 'Event Organizer',
                'fan_id' => 'ORG-0001',
                'national_id' => '1111111111',
                'phone' => '0920000000',
                'role' => 'organizer',
                'password' => Hash::make('organizer123'),
            ]
        );
    }
}
