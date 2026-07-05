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
        $search = $request->input('search');
        
        $query = Proyek::with(['lokasi', 'kontraktor']);
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_proyek', 'like', "%{$search}%")
                  ->orWhere('kode_proyek', 'like', "%{$search}%")
                  ->orWhere('tahapan_pekerjaan', 'like', "%{$search}%");
            });
        }
        
        $proyeks = $query->orderBy('nama_proyek')->get();
        return view('monitoring.timeline', compact('proyeks', 'search'));
    }

    public function persentaseProgress(Request $request)
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
 
        $progressData = $proyeks->map(function ($proyek) use ($startDate, $endDate) {
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
 
        $chartLabels = $progressData->pluck('nama_proyek')->toArray();
        $chartTargets = $progressData->pluck('target')->toArray();
        $chartActuals = $progressData->pluck('actual')->toArray();
 
        return view('monitoring.persentase_progress', compact('progressData', 'startDate', 'endDate', 'search', 'chartLabels', 'chartTargets', 'chartActuals'));
    }
}
