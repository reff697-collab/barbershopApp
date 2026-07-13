<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     *
     * Bikin 4 role (owner, kasir, barber, admin_it) sesuai desain kita,
     * lalu bikin 1 akun demo untuk masing-masing role supaya gampang
     * dites login-nya.
     */
    public function run(): void
    {
        // 1. Bikin role
        $roles = ['owner', 'kasir', 'barber', 'admin_it'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // 2. Bikin akun demo per role
        $demoUsers = [
            [
                'name' => 'Owner Demo',
                'email' => 'owner@barbershop.test',
                'role' => 'owner',
            ],
            [
                'name' => 'Kasir Demo',
                'email' => 'kasir@barbershop.test',
                'role' => 'kasir',
            ],
            [
                'name' => 'Barber Demo',
                'email' => 'barber@barbershop.test',
                'role' => 'barber',
            ],
            [
                'name' => 'Admin IT Demo',
                'email' => 'admin@barbershop.test',
                'role' => 'admin_it',
            ],
        ];

        foreach ($demoUsers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'), // ganti nanti untuk production
                    'email_verified_at' => now(),
                ]
            );

            // assignRole otomatis dari Spatie, aman dipanggil berkali-kali
            if (! $user->hasRole($data['role'])) {
                $user->assignRole($data['role']);
            }
        }
    }
}