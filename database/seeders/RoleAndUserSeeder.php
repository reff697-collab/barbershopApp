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

       // 2. Bikin akun sesuai staf asli
        $demoUsers = [
            [
                'name' => 'Owner',
                'email' => 'owner@rafelpangkasrambut.com',
                'password' => 'password',
                'role' => 'owner',
            ],
            [
                'name' => 'Kasir',
                'email' => 'kasir@rafelpangkasrambut.com',
                'password' => 'password',
                'role' => 'kasir',
            ],
            [
                'name' => 'Ary',
                'email' => 'ary@rafelpangkasrambut.com',
                'password' => 'barberbyary',
                'role' => 'barber',
            ],
            [
                'name' => 'Jodi',
                'email' => 'jodi@rafelpangkasrambut.com',
                'password' => 'barberbyjodi',
                'role' => 'barber',
            ],
            [
                'name' => 'Admin IT',
                'email' => 'admin@rafelpangkasrambut.com',
                'password' => 'password',
                'role' => 'admin_it',
            ],
        ];

        foreach ($demoUsers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
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