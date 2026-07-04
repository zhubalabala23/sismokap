<?php

namespace Database\Factories;

use App\Models\Kontraktor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kontraktor>
 */
class KontraktorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kontraktorList = [
            [
                'nama_kontraktor' => 'PT Madiun Bangun Nusantara',
                'kontak' => '0812-3456-7890',
                'alamat' => 'Jl. Panglima Sudirman No. 50, Kota Madiun, Jawa Timur'
            ],
            [
                'nama_kontraktor' => 'CV Karya Mandiri Madiun',
                'kontak' => '0857-9876-5432',
                'alamat' => 'Jl. Urip Sumoharjo No. 104, Kota Madiun, Jawa Timur'
            ],
            [
                'nama_kontraktor' => 'PT Graha Persada Konstruksi',
                'kontak' => '0821-4433-2211',
                'alamat' => 'Jl. Ring Road Barat No. 15, Kabupaten Madiun, Jawa Timur'
            ]
        ];

        $random = $this->faker->randomElement($kontraktorList);

        return [
            'nama_kontraktor' => $random['nama_kontraktor'],
            'kontak' => $random['kontak'],
            'alamat' => $random['alamat'],
        ];
    }
}
