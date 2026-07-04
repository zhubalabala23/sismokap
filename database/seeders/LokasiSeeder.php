<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Lokasi;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
        ];

        foreach ($lokasiMadiun as $lokasi) {
            Lokasi::create($lokasi);
        }
    }
}
