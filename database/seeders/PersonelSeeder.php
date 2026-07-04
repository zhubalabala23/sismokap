<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Personel;

class PersonelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $personelList = [
            [
                'nama' => 'Budi Santoso',
                'jabatan' => 'Project Manager',
                'kontak' => '0812-9876-5432'
            ],
            [
                'nama' => 'Siti Aminah',
                'jabatan' => 'Site Engineer',
                'kontak' => '0821-1234-5678'
            ],
            [
                'nama' => 'Ahmad Hidayat',
                'jabatan' => 'Safety Officer',
                'kontak' => '0857-8888-9999'
            ],
            [
                'nama' => 'Dewi Sartika',
                'jabatan' => 'Quality Control',
                'kontak' => '0813-4444-5555'
            ]
        ];

        foreach ($personelList as $personel) {
            Personel::create($personel);
        }
    }
}
