<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@p2p.com'],
            [
                'name'     => 'Super Admin',
                'email'    => 'admin@p2p.com',
                'password' => Hash::make('Admin@12345'),
            ]
        );

        $this->command->info('Super Admin created: admin@p2p.com / Admin@12345');
    }
}
