<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Account Staff / Admin
        User::factory()->create([
            'name' => 'Khairi Ibnutama',
            'email' => 'mr.ibnutama@gmail.com',
            'username' => 'developer',
            'is_staff' => true,
            'password' => Hash::make('rahasia'),
        ]);

        // Account Siswa / Student
        User::factory()->create([
            'name' => 'Budi Santoso (Siswa)',
            'email' => 'siswa1@cbt.com',
            'username' => 'siswa1',
            'is_staff' => false,
            'password' => Hash::make('siswa'),
        ]);

        User::factory()->create([
            'name' => 'Siti Rahma (Siswa)',
            'email' => 'siswa2@cbt.com',
            'username' => 'siswa2',
            'is_staff' => false,
            'password' => Hash::make('siswa'),
        ]);

        // seed 3 mata pelajaran + 150 soal tiap mapel
        $this->call(SubjectSeeder::class);
    }
}
