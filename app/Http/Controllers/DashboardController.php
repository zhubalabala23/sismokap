<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. 4 Kartu Ringkasan
        $totalProyek = Proyek::count();
        $proyekBerjalan = Proyek::where('status', 'berjalan')->count();
        $proyekSelesai = Proyek::where('status', 'selesai')->count();
        $proyekTerlambat = Proyek::where('status', 'terlambat')->count();

        // 2. Grafik line chart (Chart.js) - Dummy Data sesuai proyek di seeder
        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep'],
            'datasets' => [
                [
                    'label' => 'Gedung Serbaguna',
                    'data' => [10, 20, 32, 40, 45, 53, 60, 68, 75],
                    'borderColor' => '#0d6efd',
                    'backgroundColor' => 'transparent',
                    'tension' => 0.3
                ],
                [
                    'label' => 'Gudang Material',
                    'data' => [15, 30, 48, 60, 70, 82, 90, 93, 93],
                    'borderColor' => '#198754',
                    'backgroundColor' => 'transparent',
                    'tension' => 0.3
                ],
                [
                    'label' => 'Mess Perwira',
                    'data' => [5, 12, 25, 38, 45, 50, 62, 70, 75],
                    'borderColor' => '#fd7e14',
                    'backgroundColor' => 'transparent',
                    'tension' => 0.3
                ],
                [
                    'label' => 'Workshop',
                    'data' => [12, 22, 35, 42, 50, 58, 65, 70, 73],
                    'borderColor' => '#dc3545',
                    'backgroundColor' => 'transparent',
                    'tension' => 0.3
                ]
            ]
        ];

        // 3. Tabel "Progress Proyek" di sebelah grafik
        $allProyeks = Proyek::with(['lokasi', 'kontraktor', 'progressHarian'])->get();
        
        $progressProyek = $allProyeks->map(function ($proyek) {
            $actual = $proyek->actual_progress;
            $target = $proyek->target_progress;
            $selisih = $target - $actual;
            
            return [
                'id' => $proyek->id,
                'nama_proyek' => $proyek->nama_proyek,
                'actual' => $actual,
                'target' => $target,
                'selisih' => $selisih,
                'status' => $proyek->status
            ];
        });

        // 4. Tabel "Proyek Terbaru" (5 proyek terakhir)
        $proyekTerbaru = Proyek::with(['lokasi', 'progressHarian'])->latest()->take(5)->get();

        // 5. Section "Dokumentasi Terbaru" (4 foto)
        $realDokumentasi = Dokumentasi::latest()->take(4)->get();
        $placeholderPics = [
            'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?q=80&w=400&auto=format&fit=crop', // Construction Site
            'https://images.unsplash.com/photo-1504307651254-35680f356dfd?q=80&w=400&auto=format&fit=crop', // Steel Structure
            'https://images.unsplash.com/photo-1581094288338-2314dddb7eed?q=80&w=400&auto=format&fit=crop', // Engineers
            'https://images.unsplash.com/photo-1590069261209-f8e9b8642343?q=80&w=400&auto=format&fit=crop', // Concrete
        ];

        $dokumentasiTerbaru = [];
        for ($i = 0; $i < 4; $i++) {
            if (isset($realDokumentasi[$i])) {
                $dokumentasiTerbaru[] = [
                    'is_placeholder' => false,
                    'file_path' => asset('storage/' . $realDokumentasi[$i]->file_path),
                    'nama_proyek' => $realDokumentasi[$i]->proyek->nama_proyek,
                    'tanggal' => $realDokumentasi[$i]->tanggal_upload->format('d M Y'),
                    'keterangan' => $realDokumentasi[$i]->keterangan,
                ];
            } else {
                $pIndex = $i % count($placeholderPics);
                // Assign to one of the seeded projects
                $proyekName = isset($allProyeks[$i]) ? $allProyeks[$i]->nama_proyek : 'Proyek SISMOKAP';
                $dokumentasiTerbaru[] = [
                    'is_placeholder' => true,
                    'file_path' => $placeholderPics[$pIndex],
                    'nama_proyek' => $proyekName,
                    'tanggal' => date('d M Y', strtotime("-{$i} days")),
                    'keterangan' => 'Dokumentasi progress pembangunan fisik lapangan.',
                ];
            }
        }

        return view('dashboard', compact(
            'totalProyek',
            'proyekBerjalan',
            'proyekSelesai',
            'proyekTerlambat',
            'chartData',
            'progressProyek',
            'proyekTerbaru',
            'dokumentasiTerbaru'
        ));
    }
}
