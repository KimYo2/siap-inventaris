<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nip' => '198001012006041001',
                'name' => 'Admin BPS',
                'email' => 'admin@bps.go.id',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '199001012015041001',
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@bps.go.id',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '199505012018041001',
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@bps.go.id',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nip' => '199808012020041001',
                'name' => 'Ahmad Wijaya',
                'email' => 'ahmad.wijaya@bps.go.id',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
