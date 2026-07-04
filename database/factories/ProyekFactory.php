<?php

namespace Database\Factories;

use App\Models\Proyek;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Proyek>
 */
class ProyekFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_proyek' => 'PRJ-' . $this->faker->unique()->numberBetween(100, 999),
            'nama_proyek' => $this->faker->randomElement([
                'Gedung Serbaguna', 'Gudang Material', 'Mess Perwira', 'Workshop', 'Lapangan Upacara'
            ]),
            'lokasi_id' => \App\Models\Lokasi::factory(),
            'kontraktor_id' => \App\Models\Kontraktor::factory(),
            'tanggal_mulai' => $this->faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'tanggal_selesai' => $this->faker->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
            'target_progress' => $this->faker->randomFloat(2, 50, 100), // realistic target/progress
            'status' => $this->faker->randomElement(['berjalan', 'selesai', 'terlambat']),
        ];
    }
}
