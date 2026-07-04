<?php

namespace Database\Factories;

use App\Models\Personel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Personel>
 */
class PersonelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = [
            'Ahmad Hidayat', 'Budi Santoso', 'Eko Prasetyo', 'Hendra Wijaya',
            'Siti Aminah', 'Rian Kurniawan', 'Dewi Sartika', 'Andi Pratama'
        ];

        $jabatans = [
            'Project Manager', 'Site Engineer', 'Safety Officer', 'Quality Control',
            'Supervisor Lapangan', 'Administrasi Proyek', 'Quantity Surveyor'
        ];

        return [
            'nama' => $this->faker->randomElement($names),
            'jabatan' => $this->faker->randomElement($jabatans),
            'kontak' => '08' . $this->faker->numerify('##-####-####'),
        ];
    }
}
