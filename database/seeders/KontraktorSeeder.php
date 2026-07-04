<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Kontraktor;

class KontraktorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
            ]
        ];

        foreach ($kontraktorList as $kontraktor) {
            Kontraktor::create($kontraktor);
        }
    }
}
