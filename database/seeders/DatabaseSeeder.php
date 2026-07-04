<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin SISMOKAP',
            'email' => 'admin@sismokap.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Operator SISMOKAP',
            'email' => 'operator@sismokap.test',
            'password' => bcrypt('password'),
            'role' => 'operator',
        ]);

        User::create([
            'name' => 'Pimpinan SISMOKAP',
            'email' => 'pimpinan@sismokap.test',
            'password' => bcrypt('password'),
            'role' => 'pimpinan',
        ]);

        $this->call([
            LokasiSeeder::class,
            KontraktorSeeder::class,
            PersonelSeeder::class,
            ProyekSeeder::class,
        ]);
    }
}
