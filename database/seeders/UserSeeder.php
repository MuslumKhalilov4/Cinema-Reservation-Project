<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->createAdmin();
        $this->createSuperAdmin();
        $this->createCashier();

        User::factory()->count(20)->create()->each(function ($user) {
            $user->assignRole('user');
        });
    }

    private function createAdmin(): void
    {
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'phone' => '+994500000001',
            'password' => Hash::make('Admin123!'),
            'avatar_url' => null,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');
    }

    private function createSuperAdmin(): void
    {
        $superAdmin = User::create([
            'first_name' => 'Super Admin',
            'last_name' => 'Super Admin',
            'username' => 'super_admin',
            'email' => 'super_admin@example.com',
            'phone' => '+994500000002',
            'password' => Hash::make('SuperAdmin123!'),
            'avatar_url' => null,
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('super_admin');
    }

    private function createCashier(): void
    {
        $cashier = User::create([
            'first_name' => 'Cashier',
            'last_name' => 'Cashier',
            'username' => 'cashier',
            'email' => 'cashier@example.com',
            'phone' => '+994500000003',
            'password' => Hash::make('Cashier123!'),
            'avatar_url' => null,
            'email_verified_at' => now(),
        ]);
        $cashier->assignRole('cashier');
    }
}
