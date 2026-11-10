<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the admin user and a demo agent.
     */
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@psgroup.com')],
            ['name' => 'Administrador', 'password' => env('ADMIN_PASSWORD', 'password')],
        );

        Agent::query()->firstOrCreate(
            ['slug' => 'cristina-godoy-inciso'],
            [
                'name' => 'Cristina Godoy Inciso',
                'position' => 'Controller',
                'phone' => '+51 999 999 999',
                'whatsapp' => '51999999999',
                'email' => 'cristina@psgroup.com',
                'website' => 'https://psgroup.com',
                'is_active' => true,
            ],
        );
    }
}
