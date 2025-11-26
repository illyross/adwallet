<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@adwallet.ch'],
            [
                'name' => 'Admin',
                'password' => Hash::make('NinjaTurtles123!'),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );

        $this->command->info('Admin user created:');
        $this->command->info('Email: admin@adwallet.ch');
        $this->command->info('Password: NinjaTurtles123');
        $this->command->warn('Please change the password after first login!');
    }
}

