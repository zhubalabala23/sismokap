<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\ProgressHarian;
use App\Models\ProgressMingguan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProyekRekapExport;

class LaporanController extends Controller
{
    public function harian(Request $request)
    {
        $tanggal = $request->input('tanggal') ?: Carbon::today()->format('Y-m-d');
        
        $proyeks = Proyek::with(['lokasi', 'kontraktor', 'progressHarian'])->orderBy('nama_proyek')->get();
 
        $reportData = $proyeks->map(function ($proyek) use ($tanggal) {
            // Cari progress harian pada tanggal tersebut (di memori)
            $progress = $proyek->progressHarian
                ->filter(function ($item) use ($tanggal) {
                    $itemTanggal = $item->tanggal instanceof \Carbon\Carbon ? $item->tanggal->format('Y-m-d') : (string)$item->tanggal;
                    return $itemTanggal <= $tanggal;
                })
                ->sortByDesc(function ($item) {
                    $itemTanggal = $item->tanggal instanceof \Carbon\Carbon ? $item->tanggal->format('Y-m-d') : (string)$item->tanggal;
                    return $itemTanggal . '_' . str_pad($item->id, 10, '0', STR_PAD_LEFT);
                })
                ->first();
 
            $actual = $progress ? $progress->persentase : 0;
            $target = $proyek->target_progress;
            $selisih = $target - $actual;
 
            return [
                'id' => $proyek->id,
                'kode_proyek' => $proyek->kode_proyek,
                'nama_proyek' => $proyek->nama_proyek,
                'lokasi' => $proyek->lokasi->nama_lokasi ?? '-',
                'kontraktor' => $proyek->kontraktor->nama_kontraktor ?? '-',
                'actual' => $actual,
                'target' => $target,
                'selisih' => $selisih,
                'status' => $proyek->status,
                'keterangan' => $progress ? $progress->keterangan : 'Belum ada progress per tanggal ini.'
            ];
        });
 
        return view('laporan.harian', compact('reportData', 'tanggal'));
    }
 
    public function mingguan(Request $request)
    {
        $mingguKe = $request->input('minggu_ke') ?: (int) date('W');
        $tahun = $request->input('tahun') ?: (int) date('Y');
 
        $proyeks = Proyek::with(['lokasi', 'kontraktor', 'progressMingguan'])->orderBy('nama_proyek')->get();
 
        $reportData = $proyeks->map(function ($proyek) use ($mingguKe, $tahun) {
            // Cari progress mingguan pada minggu & tahun tersebut (di memori)
            $progress = $proyek->progressMingguan
                ->where('tahun', $tahun)
                ->where('minggu_ke', $mingguKe)
                ->first();
 
            // Jika tidak ada di minggu tersebut, cari minggu sebelumnya (di memori)
            if (!$progress) {
                $progress = $proyek->progressMingguan
                    ->filter(function($item) use ($mingguKe, $tahun) {
                        return $item->tahun < $tahun || ($item->tahun == $tahun && $item->minggu_ke < $mingguKe);
                    })
                    ->sortByDesc(function ($item) {
                        return $item->tahun . '_' . str_pad($item->minggu_ke, 3, '0', STR_PAD_LEFT);
                    })
                    ->first();
            }
 
            $actual = $progress ? $progress->persentase : 0;
            $target = $proyek->target_progress;
            $selisih = $target - $actual;
 
            return [
                'id' => $proyek->id,
                'kode_proyek' => $proyek->kode_proyek,
                'nama_proyek' => $proyek->nama_proyek,
                'lokasi' => $proyek->lokasi->nama_lokasi ?? '-',
                'kontraktor' => $proyek->kontraktor->nama_kontraktor ?? '-',
                'actual' => $actual,
                'target' => $target,
                'selisih' => $selisih,
                'status' => $proyek->status,
                'keterangan' => $progress ? $progress->keterangan : 'Belum ada rekap mingguan.'
            ];
        });
 
        return view('laporan.mingguan', compact('reportData', 'mingguKe', 'tahun'));
    }
 
    public function bulanan(Request $request)
    {
        $bulan = $request->input('bulan') ?: (int) date('m');
        $tahun = $request->input('tahun') ?: (int) date('Y');
 
        $proyeks = Proyek::with(['lokasi', 'kontraktor', 'progressHarian'])->orderBy('nama_proyek')->get();
 
        $reportData = $proyeks->map(function ($proyek) use ($bulan, $tahun) {
            // Agregat dari data harian dalam bulan itu (di memori)
            $filteredHarian = $proyek->progressHarian->filter(function ($item) use ($bulan, $tahun) {
                if ($item->tanggal instanceof \Carbon\Carbon) {
                    return $item->tanggal->month == $bulan && $item->tanggal->year == $tahun;
                }
                $itemDate = \Carbon\Carbon::parse($item->tanggal);
                return $itemDate->month == $bulan && $itemDate->year == $tahun;
            });

            $avgProgress = $filteredHarian->count() > 0 ? $filteredHarian->avg('persentase') : null;
 
            // Jika tidak ada progress harian di bulan tersebut, cari progress terakhir sebelum bulan tersebut (di memori)
            if ($avgProgress === null) {
                $startOfMonth = Carbon::create($tahun, $bulan, 1)->startOfMonth()->format('Y-m-d');
                $lastProgress = $proyek->progressHarian
                    ->filter(function ($item) use ($startOfMonth) {
                        $itemTanggal = $item->tanggal instanceof \Carbon\Carbon ? $item->tanggal->format('Y-m-d') : (string)$item->tanggal;
                        return $itemTanggal < $startOfMonth;
                    })
                    ->sortByDesc(function ($item) {
                        $itemTanggal = $item->tanggal instanceof \Carbon\Carbon ? $item->tanggal->format('Y-m-d') : (string)$item->tanggal;
                        return $itemTanggal . '_' . str_pad($item->id, 10, '0', STR_PAD_LEFT);
                    })
                    ->first();
                $actual = $lastProgress ? $lastProgress->persentase : 0;
            } else {
                $actual = round($avgProgress, 2);
            }
 
            $target = $proyek->target_progress;
            $selisih = $target - $actual;
 
            return [
                'id' => $proyek->id,
                'kode_proyek' => $proyek->kode_proyek,
                'nama_proyek' => $proyek->nama_proyek,
                'lokasi' => $proyek->lokasi->nama_lokasi ?? '-',
                'kontraktor' => $proyek->kontraktor->nama_kontraktor ?? '-',
                'actual' => $actual,
                'target' => $target,
                'selisih' => $selisih,
                'status' => $proyek->status,
            ];
        });
 
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
 
        return view('laporan.bulanan', compact('reportData', 'bulan', 'tahun', 'namaBulan'));
    }
 
    public function rekap(Request $request)
    {
        $proyeks = Proyek::with(['lokasi', 'kontraktor', 'progressHarian'])->orderBy('nama_proyek')->get();
 
        $reportData = $proyeks->map(function ($proyek) {
            // Progress Awal (entry progress harian pertama - di memori)
            $firstProgress = $proyek->progressHarian
                ->sortBy(function ($item) {
                    $itemTanggal = $item->tanggal instanceof \Carbon\Carbon ? $item->tanggal->format('Y-m-d') : (string)$item->tanggal;
                    return $itemTanggal . '_' . str_pad($item->id, 10, '0', STR_PAD_LEFT);
                })
                ->first();
            $awal = $firstProgress ? $firstProgress->persentase : 0;
 
            // Progress Terkini
            $terkini = $proyek->actual_progress;
            $target = $proyek->target_progress;
            $selisih = $target - $terkini;
 
            return [
                'id' => $proyek->id,
                'kode_proyek' => $proyek->kode_proyek,
                'nama_proyek' => $proyek->nama_proyek,
                'lokasi' => $proyek->lokasi->nama_lokasi ?? '-',
                'kontraktor' => $proyek->kontraktor->nama_kontraktor ?? '-',
                'awal' => $awal,
                'terkini' => $terkini,
                'target' => $target,
                'selisih' => $selisih,
                'status' => $proyek->status,
            ];
        });
 
        return view('laporan.rekap', compact('reportData'));
    }

    public function exportPdf(Request $request, $id)
    {
        $proyek = Proyek::with(['lokasi', 'kontraktor'])->findOrFail($id);
        
        // Get latest 4 documentation photos
        $dbDokumentasis = $proyek->dokumentasi()
            ->orderBy('tanggal_upload', 'desc')
            ->limit(4)
            ->get();

        $dokumentasis = [];
        foreach ($dbDokumentasis as $foto) {
            $path = storage_path('app/public/' . $foto->file_path);
            $base64 = '';
            if (file_exists($path)) {
                $data = file_get_contents($path);
                $base64 = 'data:image/' . pathinfo($path, PATHINFO_EXTENSION) . ';base64,' . base64_encode($data);
            } else {
                $base64 = 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=500';
            }

            $dokumentasis[] = [
                'base64_src' => $base64,
                'keterangan' => $foto->keterangan,
                'tanggal' => $foto->tanggal_upload->format('d M Y')
            ];
        }

        $pdf = Pdf::loadView('laporan.pdf', compact('proyek', 'dokumentasis'));
        return $pdf->download('laporan-progres-' . strtolower($proyek->kode_proyek) . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new ProyekRekapExport, 'rekap_progress_' . date('Ymd_His') . '.xlsx');
    }
}
