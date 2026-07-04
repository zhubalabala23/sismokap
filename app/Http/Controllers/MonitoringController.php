<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\ProgressHarian;
use App\Models\ProgressMingguan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MonitoringController extends Controller
{
    public function timeline(Request $request)
    {
        $proyeks = Proyek::orderBy('nama_proyek')->get();
        $proyekId = $request->input('proyek_id') ?: ($proyeks->first()?->id ?? null);
        
        $selectedProyek = null;
        $timelineData = collect();

        if ($proyekId) {
            $selectedProyek = Proyek::findOrFail($proyekId);

            $harian = $selectedProyek->progressHarian()->with('user')->get()->map(function ($item) {
                return [
                    'tanggal' => $item->tanggal,
                    'tanggal_formatted' => $item->tanggal->format('d M Y'),
                    'tipe' => 'Harian',
                    'persentase' => $item->persentase,
                    'keterangan' => $item->keterangan,
                    'input_by' => $item->user->name ?? 'Unknown',
                    'badge_color' => 'bg-info text-dark'
                ];
            });

            $mingguan = $selectedProyek->progressMingguan()->get()->map(function ($item) {
                $date = new \DateTime();
                $date->setISODate($item->tahun, $item->minggu_ke);
                $carbonDate = Carbon::instance($date);

                return [
                    'tanggal' => $carbonDate,
                    'tanggal_formatted' => "Minggu ke-{$item->minggu_ke} ({$item->tahun})",
                    'tipe' => 'Mingguan',
                    'persentase' => $item->persentase,
                    'keterangan' => $item->keterangan,
                    'input_by' => 'Operator',
                    'badge_color' => 'bg-warning text-dark'
                ];
            });

            $timelineData = $harian->concat($mingguan)->sortByDesc('tanggal');
        }

        return view('monitoring.timeline', compact('proyeks', 'proyekId', 'selectedProyek', 'timelineData'));
    }

    public function persentaseProgress(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $proyeks = Proyek::with(['lokasi', 'kontraktor', 'progressHarian'])->orderBy('nama_proyek')->get();

        $progressData = $proyeks->map(function ($proyek) use ($startDate, $endDate) {
            // Filter progress harian berdasarkan range tanggal jika disediakan (di memori)
            $harian = $proyek->progressHarian;
            
            if ($startDate) {
                $harian = $harian->filter(function ($item) use ($startDate) {
                    $itemTanggal = $item->tanggal instanceof \Carbon\Carbon ? $item->tanggal->format('Y-m-d') : (string)$item->tanggal;
                    return $itemTanggal >= $startDate;
                });
            }
            if ($endDate) {
                $harian = $harian->filter(function ($item) use ($endDate) {
                    $itemTanggal = $item->tanggal instanceof \Carbon\Carbon ? $item->tanggal->format('Y-m-d') : (string)$item->tanggal;
                    return $itemTanggal <= $endDate;
                });
            }

            $latestProgress = $harian
                ->sortByDesc(function ($item) {
                    $itemTanggal = $item->tanggal instanceof \Carbon\Carbon ? $item->tanggal->format('Y-m-d') : (string)$item->tanggal;
                    return $itemTanggal . '_' . str_pad($item->id, 10, '0', STR_PAD_LEFT);
                })
                ->first();

            $actual = $latestProgress ? $latestProgress->persentase : $proyek->actual_progress;
            
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

        return view('monitoring.persentase_progress', compact('progressData', 'startDate', 'endDate'));
    }
}
