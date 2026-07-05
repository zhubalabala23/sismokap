<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Lokasi;
use App\Models\Kontraktor;
use App\Models\Proyek;

class ProyekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            ["name" => "Gedung Serbaguna", "jenis" => "Gedung", "nilai" => 3500000000.00, "keterangan" => "Pembangunan gedung serbaguna militer tingkat 2."],
            ["name" => "Gudang Material", "jenis" => "Gudang", "nilai" => 1800000000.00, "keterangan" => "Penyimpanan logistik material konstruksi."],
            ["name" => "Mess Perwira", "jenis" => "Mess", "nilai" => 2400000000.00, "keterangan" => "Fasilitas tempat tinggal perwira staf."],
            ["name" => "Workshop", "jenis" => "Workshop", "nilai" => 1500000000.00, "keterangan" => "Bengkel perbaikan alat transportasi taktis."],
            ["name" => "Lapangan Upacara", "jenis" => "Infrastruktur", "nilai" => 950000000.00, "keterangan" => "Perataan jalan dan semenisasi lapangan utama."]
        ];

        $lokasiIds = Lokasi::pluck('id')->toArray();
        $kontraktorIds = Kontraktor::pluck('id')->toArray();

        foreach ($projects as $index => $proj) {
            Proyek::create([
                'kode_proyek' => 'PRJ-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'nama_proyek' => $proj['name'],
                'jenis_pekerjaan' => $proj['jenis'],
                'nilai_kontrak' => $proj['nilai'],
                'keterangan' => $proj['keterangan'],
                'lokasi_id' => fake()->randomElement($lokasiIds),
                'kontraktor_id' => fake()->randomElement($kontraktorIds),
                'tanggal_mulai' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
                'tanggal_selesai' => fake()->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
                'target_progress' => fake()->randomFloat(2, 50, 100),
                'status' => fake()->randomElement(['berjalan', 'selesai', 'terlambat']),
            ]);
        }
    }
}
