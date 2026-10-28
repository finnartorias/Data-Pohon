<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Default
        User::updateOrCreate(
            ['email' => 'admin@pohon.id'],
            [
                'name' => 'Administrator Pohon',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Impor Data Pohon Nyata Pengukuran Karbon Sekolah (Tanpa GPS)
        $this->call(SekolahTreeSeeder::class);
    }
}
