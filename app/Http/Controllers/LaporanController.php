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
        $proyekId = $request->input('proyek_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
 
        $query = ProgressHarian::with(['proyek.lokasi', 'proyek.kontraktor', 'user']);
 
        if ($proyekId) {
            $query->where('proyek_id', $proyekId);
        }
        if ($startDate) {
            $query->whereDate('tanggal_pelaksanaan', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('tanggal_pelaksanaan', '<=', $endDate);
        }
 
        $reportData = $query->orderBy('tanggal_pelaksanaan', 'desc')->orderBy('id', 'desc')->get();
 
        $allProyeks = Proyek::orderBy('nama_proyek')->get();
 
        return view('laporan.harian', compact('allProyeks', 'reportData', 'proyekId', 'startDate', 'endDate'));
    }
 
    public function exportHarianPdf(Request $request)
    {
        $proyekId = $request->input('proyek_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
 
        $query = ProgressHarian::with(['proyek.lokasi', 'proyek.kontraktor', 'user']);
 
        if ($proyekId) {
            $query->where('proyek_id', $proyekId);
        }
        if ($startDate) {
            $query->whereDate('tanggal_pelaksanaan', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('tanggal_pelaksanaan', '<=', $endDate);
        }
 
        $reportData = $query->orderBy('tanggal_pelaksanaan', 'desc')->orderBy('id', 'desc')->get();
 
        $pdf = Pdf::loadView('laporan.harian_pdf', compact('reportData', 'startDate', 'endDate'));
        return $pdf->download('laporan-harian-' . date('Ymd_His') . '.pdf');
    }
 
    public function mingguan(Request $request)
    {
        $proyekId = $request->input('proyek_id');
        $mingguKe = $request->input('minggu_ke');
        $tahun = $request->input('tahun');
 
        $query = ProgressMingguan::with(['proyek.lokasi', 'proyek.kontraktor']);
 
        if ($proyekId) {
            $query->where('proyek_id', $proyekId);
        }
        if ($mingguKe) {
            $query->where('minggu_ke', $mingguKe);
        }
        if ($tahun) {
            $query->where('tahun', $tahun);
        }
 
        $reportData = $query->orderBy('tahun', 'desc')->orderBy('minggu_ke', 'desc')->orderBy('id', 'desc')->get();
 
        $allProyeks = Proyek::orderBy('nama_proyek')->get();
 
        return view('laporan.mingguan', compact('allProyeks', 'reportData', 'proyekId', 'mingguKe', 'tahun'));
    }
 
    public function exportMingguanPdf(Request $request)
    {
        $proyekId = $request->input('proyek_id');
        $mingguKe = $request->input('minggu_ke');
        $tahun = $request->input('tahun');
 
        $query = ProgressMingguan::with(['proyek.lokasi', 'proyek.kontraktor']);
 
        if ($proyekId) {
            $query->where('proyek_id', $proyekId);
        }
        if ($mingguKe) {
            $query->where('minggu_ke', $mingguKe);
        }
        if ($tahun) {
            $query->where('tahun', $tahun);
        }
 
        $reportData = $query->orderBy('tahun', 'desc')->orderBy('minggu_ke', 'desc')->orderBy('id', 'desc')->get();
 
        $pdf = Pdf::loadView('laporan.mingguan_pdf', compact('reportData', 'mingguKe', 'tahun'));
        return $pdf->download('laporan-mingguan-' . date('Ymd_His') . '.pdf');
    }
 
    public function bulanan(Request $request)
    {
        $proyekId = $request->input('proyek_id');
        $bulan = $request->input('bulan') ?: (int) date('m');
        $tahun = $request->input('tahun') ?: (int) date('Y');

        $proyeksQuery = Proyek::with(['lokasi', 'kontraktor', 'progressHarian', 'progressMingguan']);
        if ($proyekId) {
            $proyeksQuery->where('id', $proyekId);
        }
        $proyeks = $proyeksQuery->orderBy('nama_proyek')->get();

        $reportData = $proyeks->map(function ($proyek) use ($bulan, $tahun) {
            // Filter harian logs in month
            $harianInMonth = $proyek->progressHarian->filter(function ($item) use ($bulan, $tahun) {
                if ($item->tanggal_pelaksanaan instanceof \Carbon\Carbon) {
                    return $item->tanggal_pelaksanaan->month == $bulan && $item->tanggal_pelaksanaan->year == $tahun;
                }
                $itemDate = \Carbon\Carbon::parse($item->tanggal_pelaksanaan);
                return $itemDate->month == $bulan && $itemDate->year == $tahun;
            });

            // Filter weekly logs in month
            $mingguanInMonth = $proyek->progressMingguan->filter(function ($item) use ($bulan, $tahun) {
                $startOfWeek = \Carbon\Carbon::now()->setISODate($item->tahun, $item->minggu_ke)->startOfWeek();
                $endOfWeek = \Carbon\Carbon::now()->setISODate($item->tahun, $item->minggu_ke)->endOfWeek();
                return ($startOfWeek->month == $bulan && $startOfWeek->year == $tahun) || ($endOfWeek->month == $bulan && $endOfWeek->year == $tahun);
            });

            if ($harianInMonth->isEmpty() && $mingguanInMonth->isEmpty()) {
                return null;
            }

            $dailyGain = $harianInMonth->sum('progres_harian');
            $weeklyGain = $mingguanInMonth->sum('progress_berjalan');

            // Akumulasi Progress:
            // Ambil data kumulatif dari progres harian teranyar di bulan ini atau sebelumnya
            $latestHarian = $proyek->progressHarian->filter(function ($item) use ($bulan, $tahun) {
                $limitDate = \Carbon\Carbon::create($tahun, $bulan, 1)->endOfMonth();
                if ($item->tanggal_pelaksanaan instanceof \Carbon\Carbon) {
                    return $item->tanggal_pelaksanaan->lte($limitDate);
                }
                return \Carbon\Carbon::parse($item->tanggal_pelaksanaan)->lte($limitDate);
            })->sortByDesc(function ($item) {
                $itemTanggal = $item->tanggal_pelaksanaan instanceof \Carbon\Carbon ? $item->tanggal_pelaksanaan->format('Y-m-d') : (string)$item->tanggal_pelaksanaan;
                return $itemTanggal . '_' . str_pad($item->id, 10, '0', STR_PAD_LEFT);
            })->first();

            // Ambil data kumulatif dari progres mingguan teranyar di bulan ini atau sebelumnya
            $latestMingguan = $proyek->progressMingguan->filter(function ($item) use ($bulan, $tahun) {
                $startOfWeek = \Carbon\Carbon::now()->setISODate($item->tahun, $item->minggu_ke)->startOfWeek();
                $limitDate = \Carbon\Carbon::create($tahun, $bulan, 1)->endOfMonth();
                return $startOfWeek->lte($limitDate);
            })->sortByDesc(function ($item) {
                return $item->tahun . '_' . str_pad($item->minggu_ke, 3, '0', STR_PAD_LEFT) . '_' . $item->id;
            })->first();

            $harianCumulative = $latestHarian ? $latestHarian->persentase : 0;
            $mingguanCumulative = $latestMingguan ? $latestMingguan->persentase : 0;

            $actual = max($harianCumulative, $mingguanCumulative);
            $target = $proyek->target_progress;
            $selisih = $actual - $target;

            return [
                'id' => $proyek->id,
                'kode_proyek' => $proyek->kode_proyek,
                'nama_proyek' => $proyek->nama_proyek,
                'lokasi' => $proyek->lokasi?->nama_lokasi ?? '-',
                'kontraktor' => $proyek->kontraktor?->nama_kontraktor ?? '-',
                'daily_gain' => $dailyGain,
                'weekly_gain' => $weeklyGain,
                'actual' => $actual,
                'target' => $target,
                'selisih' => $selisih,
                'status' => $proyek->status,
            ];
        })->filter()->values();

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $allProyeks = Proyek::orderBy('nama_proyek')->get();

        return view('laporan.bulanan', compact('allProyeks', 'reportData', 'proyekId', 'bulan', 'tahun', 'namaBulan'));
    }

    public function exportBulananPdf(Request $request)
    {
        $proyekId = $request->input('proyek_id');
        $bulan = $request->input('bulan') ?: (int) date('m');
        $tahun = $request->input('tahun') ?: (int) date('Y');

        $proyeksQuery = Proyek::with(['lokasi', 'kontraktor', 'progressHarian', 'progressMingguan']);
        if ($proyekId) {
            $proyeksQuery->where('id', $proyekId);
        }
        $proyeks = $proyeksQuery->orderBy('nama_proyek')->get();

        $reportData = $proyeks->map(function ($proyek) use ($bulan, $tahun) {
            // Filter harian logs in month
            $harianInMonth = $proyek->progressHarian->filter(function ($item) use ($bulan, $tahun) {
                if ($item->tanggal_pelaksanaan instanceof \Carbon\Carbon) {
                    return $item->tanggal_pelaksanaan->month == $bulan && $item->tanggal_pelaksanaan->year == $tahun;
                }
                $itemDate = \Carbon\Carbon::parse($item->tanggal_pelaksanaan);
                return $itemDate->month == $bulan && $itemDate->year == $tahun;
            });

            // Filter weekly logs in month
            $mingguanInMonth = $proyek->progressMingguan->filter(function ($item) use ($bulan, $tahun) {
                $startOfWeek = \Carbon\Carbon::now()->setISODate($item->tahun, $item->minggu_ke)->startOfWeek();
                $endOfWeek = \Carbon\Carbon::now()->setISODate($item->tahun, $item->minggu_ke)->endOfWeek();
                return ($startOfWeek->month == $bulan && $startOfWeek->year == $tahun) || ($endOfWeek->month == $bulan && $endOfWeek->year == $tahun);
            });

            if ($harianInMonth->isEmpty() && $mingguanInMonth->isEmpty()) {
                return null;
            }

            $dailyGain = $harianInMonth->sum('progres_harian');
            $weeklyGain = $mingguanInMonth->sum('progress_berjalan');

            // Akumulasi Progress:
            // Ambil data kumulatif dari progres harian teranyar di bulan ini atau sebelumnya
            $latestHarian = $proyek->progressHarian->filter(function ($item) use ($bulan, $tahun) {
                $limitDate = \Carbon\Carbon::create($tahun, $bulan, 1)->endOfMonth();
                if ($item->tanggal_pelaksanaan instanceof \Carbon\Carbon) {
                    return $item->tanggal_pelaksanaan->lte($limitDate);
                }
                return \Carbon\Carbon::parse($item->tanggal_pelaksanaan)->lte($limitDate);
            })->sortByDesc(function ($item) {
                $itemTanggal = $item->tanggal_pelaksanaan instanceof \Carbon\Carbon ? $item->tanggal_pelaksanaan->format('Y-m-d') : (string)$item->tanggal_pelaksanaan;
                return $itemTanggal . '_' . str_pad($item->id, 10, '0', STR_PAD_LEFT);
            })->first();

            // Ambil data kumulatif dari progres mingguan teranyar di bulan ini atau sebelumnya
            $latestMingguan = $proyek->progressMingguan->filter(function ($item) use ($bulan, $tahun) {
                $startOfWeek = \Carbon\Carbon::now()->setISODate($item->tahun, $item->minggu_ke)->startOfWeek();
                $limitDate = \Carbon\Carbon::create($tahun, $bulan, 1)->endOfMonth();
                return $startOfWeek->lte($limitDate);
            })->sortByDesc(function ($item) {
                return $item->tahun . '_' . str_pad($item->minggu_ke, 3, '0', STR_PAD_LEFT) . '_' . $item->id;
            })->first();

            $harianCumulative = $latestHarian ? $latestHarian->persentase : 0;
            $mingguanCumulative = $latestMingguan ? $latestMingguan->persentase : 0;

            $actual = max($harianCumulative, $mingguanCumulative);
            $target = $proyek->target_progress;
            $selisih = $actual - $target;

            return [
                'id' => $proyek->id,
                'kode_proyek' => $proyek->kode_proyek,
                'nama_proyek' => $proyek->nama_proyek,
                'lokasi' => $proyek->lokasi?->nama_lokasi ?? '-',
                'kontraktor' => $proyek->kontraktor?->nama_kontraktor ?? '-',
                'daily_gain' => $dailyGain,
                'weekly_gain' => $weeklyGain,
                'actual' => $actual,
                'target' => $target,
                'selisih' => $selisih,
                'status' => $proyek->status,
            ];
        })->filter()->values();

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $pdf = Pdf::loadView('laporan.bulanan_pdf', compact('reportData', 'bulan', 'tahun', 'namaBulan'));
        return $pdf->download('laporan-bulanan-' . date('Ymd_His') . '.pdf');
    }
 
    public function rekap(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        $query = Proyek::with(['lokasi', 'kontraktor', 'progressHarian']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_proyek', 'like', "%{$search}%")
                  ->orWhere('kode_proyek', 'like', "%{$search}%");
            });
        }

        $proyeks = $query->orderBy('nama_proyek')->get();

        $reportData = $proyeks->map(function ($proyek) use ($startDate, $endDate) {
            $harian = $proyek->progressHarian;
            
            if ($startDate) {
                $harian = $harian->filter(function ($item) use ($startDate) {
                    $itemTanggal = $item->tanggal_pelaksanaan instanceof \Carbon\Carbon ? $item->tanggal_pelaksanaan->format('Y-m-d') : (string)$item->tanggal_pelaksanaan;
                    return $itemTanggal >= $startDate;
                });
            }
            if ($endDate) {
                $harian = $harian->filter(function ($item) use ($endDate) {
                    $itemTanggal = $item->tanggal_pelaksanaan instanceof \Carbon\Carbon ? $item->tanggal_pelaksanaan->format('Y-m-d') : (string)$item->tanggal_pelaksanaan;
                    return $itemTanggal <= $endDate;
                });
            }

            $latestProgress = $harian
                ->sortByDesc(function ($item) {
                    $itemTanggal = $item->tanggal_pelaksanaan instanceof \Carbon\Carbon ? $item->tanggal_pelaksanaan->format('Y-m-d') : (string)$item->tanggal_pelaksanaan;
                    return $itemTanggal . '_' . str_pad($item->id, 10, '0', STR_PAD_LEFT);
                })
                ->first();

            $actual = $latestProgress ? $latestProgress->persentase : $proyek->actual_progress;
            
            $target = $proyek->target_progress;
            $selisih = $actual - $target;

            return [
                'id' => $proyek->id,
                'kode_proyek' => $proyek->kode_proyek,
                'nama_proyek' => $proyek->nama_proyek,
                'lokasi' => $proyek->lokasi?->nama_lokasi ?? '-',
                'kontraktor' => $proyek->kontraktor?->nama_kontraktor ?? '-',
                'actual' => $actual,
                'target' => $target,
                'selisih' => $selisih,
                'status' => $proyek->status,
            ];
        });

        return view('laporan.rekap', compact('reportData', 'startDate', 'endDate', 'search'));
    }

    public function exportRekapPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        $query = Proyek::with(['lokasi', 'kontraktor', 'progressHarian']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_proyek', 'like', "%{$search}%")
                  ->orWhere('kode_proyek', 'like', "%{$search}%");
            });
        }

        $proyeks = $query->orderBy('nama_proyek')->get();

        $reportData = $proyeks->map(function ($proyek) use ($startDate, $endDate) {
            $harian = $proyek->progressHarian;
            
            if ($startDate) {
                $harian = $harian->filter(function ($item) use ($startDate) {
                    $itemTanggal = $item->tanggal_pelaksanaan instanceof \Carbon\Carbon ? $item->tanggal_pelaksanaan->format('Y-m-d') : (string)$item->tanggal_pelaksanaan;
                    return $itemTanggal >= $startDate;
                });
            }
            if ($endDate) {
                $harian = $harian->filter(function ($item) use ($endDate) {
                    $itemTanggal = $item->tanggal_pelaksanaan instanceof \Carbon\Carbon ? $item->tanggal_pelaksanaan->format('Y-m-d') : (string)$item->tanggal_pelaksanaan;
                    return $itemTanggal <= $endDate;
                });
            }

            $latestProgress = $harian
                ->sortByDesc(function ($item) {
                    $itemTanggal = $item->tanggal_pelaksanaan instanceof \Carbon\Carbon ? $item->tanggal_pelaksanaan->format('Y-m-d') : (string)$item->tanggal_pelaksanaan;
                    return $itemTanggal . '_' . str_pad($item->id, 10, '0', STR_PAD_LEFT);
                })
                ->first();

            $actual = $latestProgress ? $latestProgress->persentase : $proyek->actual_progress;
            
            $target = $proyek->target_progress;
            $selisih = $actual - $target;

            return [
                'id' => $proyek->id,
                'kode_proyek' => $proyek->kode_proyek,
                'nama_proyek' => $proyek->nama_proyek,
                'lokasi' => $proyek->lokasi?->nama_lokasi ?? '-',
                'kontraktor' => $proyek->kontraktor?->nama_kontraktor ?? '-',
                'actual' => $actual,
                'target' => $target,
                'selisih' => $selisih,
                'status' => $proyek->status,
            ];
        });

        $pdf = Pdf::loadView('laporan.rekap_pdf', compact('reportData', 'startDate', 'endDate'));
        return $pdf->download('laporan-rekap-progres-' . date('Ymd_His') . '.pdf');
    }

    public function exportPdf(Request $request, $id)
    {
        $proyek = Proyek::with(['lokasi', 'kontraktor'])->findOrFail($id);
        
        // Get latest 4 documentation photos
        $dbDokumentasis = $proyek->dokumentasi()
            ->orderBy('tanggal_upload', 'desc')
            ->limit(4)
            ->get();

        $disk = config('filesystems.default');
        $storage = \Illuminate\Support\Facades\Storage::disk($disk);

        $dokumentasis = [];
        foreach ($dbDokumentasis as $foto) {
            $base64 = '';
            if ($foto->file_path) {
                if (filter_var($foto->file_path, FILTER_VALIDATE_URL)) {
                    $base64 = $foto->file_path;
                } elseif ($storage->exists($foto->file_path)) {
                    $data = $storage->get($foto->file_path);
                    $ext = pathinfo($foto->file_path, PATHINFO_EXTENSION);
                    $base64 = 'data:image/' . $ext . ';base64,' . base64_encode($data);
                } else {
                    $base64 = 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?w=500';
                }
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
