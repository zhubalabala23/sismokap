<?php

namespace Database\Factories;

use App\Models\Lokasi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lokasi>
 */
class LokasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $lokasiMadiun = [
            [
                'nama_lokasi' => 'Madiun Kota (Kartoharjo)',
                'alamat' => 'Jl. Pahlawan No. 12, Kecamatan Kartoharjo, Kota Madiun, Jawa Timur'
            ],
            [
                'nama_lokasi' => 'Madiun Kabupaten (Jiwan)',
                'alamat' => 'Jl. Raya Solo-Madiun No. 45, Kecamatan Jiwan, Kabupaten Madiun, Jawa Timur'
            ],
            [
                'nama_lokasi' => 'Madiun Kota (Taman)',
                'alamat' => 'Jl. Cokroaminoto No. 88, Kecamatan Taman, Kota Madiun, Jawa Timur'
            ],
            [
                'nama_lokasi' => 'Madiun Kabupaten (Mejayan)',
                'alamat' => 'Jl. Ahmad Yani No. 101, Caruban, Kecamatan Mejayan, Kabupaten Madiun, Jawa Timur'
            ]
        ];

        $random = $this->faker->randomElement($lokasiMadiun);

        return [
            'nama_lokasi' => $random['nama_lokasi'],
            'alamat' => $random['alamat'],
        ];
    }
}
