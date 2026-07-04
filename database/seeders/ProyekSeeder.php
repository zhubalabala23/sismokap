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
        $projectNames = [
            "Gedung Serbaguna",
            "Gudang Material",
            "Mess Perwira",
            "Workshop",
            "Lapangan Upacara"
        ];

        $lokasiIds = Lokasi::pluck('id')->toArray();
        $kontraktorIds = Kontraktor::pluck('id')->toArray();

        foreach ($projectNames as $index => $name) {
            Proyek::create([
                'kode_proyek' => 'PRJ-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'nama_proyek' => $name,
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
