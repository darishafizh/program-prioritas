<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'superadmin@roren.com'],
            [
                'name' => 'Super Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('R0r3N@sup3r'),
                'role' => 'Super Admin'
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'admin@roren.com'],
            [
                'name' => 'Admin Roren',
                'password' => \Illuminate\Support\Facades\Hash::make('r0rEn9$pr!or!ta5'),
                'role' => 'Admin'
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'admindjpt@roren.com'],
            [
                'name' => 'Admin DJPT',
                'password' => \Illuminate\Support\Facades\Hash::make('djpt9$pr!or!ta5'),
                'role' => 'Verifikator'
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'bandungbarat@roren.com'],
            [
                'name' => 'Bandung Barat',
                'password' => \Illuminate\Support\Facades\Hash::make('D43r4H@bdg'),
                'role' => 'User Daerah'
            ]
        );
    }
}
