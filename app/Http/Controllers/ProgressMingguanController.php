<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\ProgressMingguan;
use App\Http\Requests\StoreProgressMingguanRequest;
use Illuminate\Http\Request;

class ProgressMingguanController extends Controller
{
    public function index(Request $request)
    {
        $proyekId = $request->input('proyek_id');

        $query = ProgressMingguan::with('proyek')->orderBy('tahun', 'desc')->orderBy('minggu_ke', 'desc');

        if ($proyekId) {
            $query->where('proyek_id', $proyekId);
        }

        $progressMingguans = $query->paginate(10)->withQueryString();
        $proyeks = Proyek::orderBy('nama_proyek')->get();

        return view('monitoring.progress_mingguan', compact('progressMingguans', 'proyeks', 'proyekId'));
    }

    public function store(StoreProgressMingguanRequest $request)
    {
        $progress = ProgressMingguan::create($request->validated());

        // Estimasi tanggal dari minggu_ke dan tahun (mengambil hari Senin di minggu tersebut)
        $date = new \DateTime();
        $date->setISODate($request->tahun, $request->minggu_ke);
        $tanggal = $date->format('Y-m-d');

        // Auto update status proyek
        $proyek = Proyek::findOrFail($request->proyek_id);
        $proyek->updateStatusBasedOnProgress($request->persentase, $tanggal);

        return redirect()->route('progress-mingguan.index')
            ->with('success', 'Progress mingguan berhasil ditambahkan dan status proyek diperbarui.');
    }
}
