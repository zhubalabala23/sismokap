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
                'kabupaten_kota' => 'Kota Madiun',
                'provinsi' => 'Jawa Timur',
                'latitude' => -7.62980000,
                'longitude' => 111.52430000,
                'alamat' => 'Jl. Pahlawan No. 12, Kecamatan Kartoharjo, Kota Madiun, Jawa Timur',
                'keterangan_lokasi' => 'Pusat pemerintahan Kota Madiun dekat Balai Kota.'
            ],
            [
                'nama_lokasi' => 'Madiun Kabupaten (Jiwan)',
                'kabupaten_kota' => 'Kabupaten Madiun',
                'provinsi' => 'Jawa Timur',
                'latitude' => -7.61890000,
                'longitude' => 111.49820000,
                'alamat' => 'Jl. Raya Solo-Madiun No. 45, Kecamatan Jiwan, Kabupaten Madiun, Jawa Timur',
                'keterangan_lokasi' => 'Wilayah perbatasan antara Kota Madiun dan Magetan.'
            ],
            [
                'nama_lokasi' => 'Madiun Kota (Taman)',
                'kabupaten_kota' => 'Kota Madiun',
                'provinsi' => 'Jawa Timur',
                'latitude' => -7.64020000,
                'longitude' => 111.53050000,
                'alamat' => 'Jl. Cokroaminoto No. 88, Kecamatan Taman, Kota Madiun, Jawa Timur',
                'keterangan_lokasi' => 'Daerah komersil padat penduduk.'
            ],
        ];

        foreach ($lokasiMadiun as $lokasi) {
            Lokasi::create($lokasi);
        }
    }
}
