<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\ProgressHarian;
use App\Http\Requests\StoreProgressHarianRequest;
use Illuminate\Http\Request;

class ProgressHarianController extends Controller
{
    public function index(Request $request)
    {
        $proyekId = $request->input('proyek_id');

        $query = ProgressHarian::with(['proyek', 'user'])->orderBy('tanggal_pelaksanaan', 'desc')->orderBy('id', 'desc');

        if ($proyekId) {
            $query->where('proyek_id', $proyekId);
        }

        $progressHarians = $query->paginate(10)->withQueryString();
        $proyeks = Proyek::orderBy('nama_proyek')->get();

        return view('monitoring.progress_harian', compact('progressHarians', 'proyeks', 'proyekId'));
    }

    public function store(StoreProgressHarianRequest $request)
    {
        $data = $request->validated();
        $data['input_by'] = auth()->id();

        $progress = ProgressHarian::create($data);

        // Auto update status proyek
        $proyek = Proyek::findOrFail($request->proyek_id);
        $proyek->updateStatusBasedOnProgress($request->persentase, $request->tanggal_pelaksanaan);

        return redirect()->route('progress-harian.index')
            ->with('success', 'Progress harian berhasil ditambahkan dan status proyek diperbarui.');
    }
}
