<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Combine counts into a single query to reduce latency
        $proyekCounts = Proyek::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'berjalan' THEN 1 ELSE 0 END) as berjalan,
            SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai,
            SUM(CASE WHEN status = 'terlambat' THEN 1 ELSE 0 END) as terlambat
        ")->first();

        $totalProyek = (int) ($proyekCounts->total ?? 0);
        $proyekBerjalan = (int) ($proyekCounts->berjalan ?? 0);
        $proyekSelesai = (int) ($proyekCounts->selesai ?? 0);
        $proyekTerlambat = (int) ($proyekCounts->terlambat ?? 0);

        // Year filter setup
        $selectedYear = $request->input('year') ?: (int) date('Y');
        $years = range(date('Y') - 5, date('Y') + 5);

        // 2. Fetch all projects with relations in ONE SINGLE QUERY
        $allProyeks = Proyek::with(['lokasi', 'kontraktor', 'progressHarian'])->get();

        // 3. Line chart data using the loaded collections
        $chartData = [
            'labels' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            'datasets' => []
        ];

        $colors = [
            '#0d6efd', // Blue
            '#198754', // Green
            '#fd7e14', // Orange
            '#dc3545', // Red
            '#6f42c1', // Purple
            '#0dcaf0', // Cyan
            '#20c997', // Teal
        ];

        foreach ($allProyeks as $index => $proyek) {
            $datasetData = [];
            $color = $colors[$index % count($colors)];

            // Sort progressHarian in memory to avoid query overhead
            $sortedProgress = $proyek->progressHarian->sortBy('tanggal_pelaksanaan');

            for ($month = 1; $month <= 12; $month++) {
                $lastDayOfMonth = \Carbon\Carbon::create($selectedYear, $month, 1)->endOfMonth()->format('Y-m-d');
                
                $latestProgress = $sortedProgress
                    ->filter(function($item) use ($lastDayOfMonth) {
                        $itemTanggal = $item->tanggal_pelaksanaan instanceof \Carbon\Carbon ? $item->tanggal_pelaksanaan->format('Y-m-d') : (string)$item->tanggal_pelaksanaan;
                        return $itemTanggal <= $lastDayOfMonth;
                    })
                    ->sortByDesc(function ($item) {
                        $itemTanggal = $item->tanggal_pelaksanaan instanceof \Carbon\Carbon ? $item->tanggal_pelaksanaan->format('Y-m-d') : (string)$item->tanggal_pelaksanaan;
                        return $itemTanggal . '_' . str_pad($item->id, 10, '0', STR_PAD_LEFT);
                    })
                    ->first();

                $datasetData[] = $latestProgress ? (float)$latestProgress->persentase : 0.0;
            }

            $chartData['datasets'][] = [
                'label' => $proyek->nama_proyek,
                'data' => $datasetData,
                'borderColor' => $color,
                'backgroundColor' => 'transparent',
                'tension' => 0.3
            ];
        }

        // 4. Progress Proyek table mapping
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

        // 5. Proyek Terbaru (reuse loaded collection)
        $proyekTerbaru = $allProyeks->sortByDesc('created_at')->take(5);

        // 6. Section "Dokumentasi Terbaru"
        $realDokumentasi = Dokumentasi::with('proyek')->latest()->take(4)->get();
        $dokumentasiTerbaru = [];
        foreach ($realDokumentasi as $doc) {
            if ($doc->proyek) {
                $dokumentasiTerbaru[] = [
                    'file_path' => $doc->file_url,
                    'video_path' => $doc->video_url,
                    'jenis_dokumentasi' => $doc->jenis_dokumentasi,
                    'nama_proyek' => $doc->proyek->nama_proyek,
                    'actual_progress' => $doc->proyek->actual_progress,
                    'tanggal' => $doc->tanggal_upload->format('d M Y'),
                    'keterangan' => $doc->keterangan,
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
            'dokumentasiTerbaru',
            'selectedYear',
            'years'
        ));
    }
}
