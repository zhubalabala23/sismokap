<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\Dokumentasi;
use App\Http\Requests\StoreDokumentasiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    public function index(Request $request)
    {
        $proyekId = $request->input('proyek_id');

        // Fetch projects with their documentation
        $proyeks = Proyek::orderBy('nama_proyek')->get();

        $query = Proyek::with(['dokumentasi' => function ($q) {
            $q->orderBy('tanggal_upload', 'desc');
        }]);

        if ($proyekId) {
            $query->where('id', $proyekId);
        }

        $galleryProyeks = $query->get();

        return view('monitoring.dokumentasi', compact('galleryProyeks', 'proyeks', 'proyekId'));
    }

    public function store(StoreDokumentasiRequest $request)
    {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('dokumentasi', 'public');

            Dokumentasi::create([
                'proyek_id' => $request->proyek_id,
                'file_path' => $path,
                'tanggal_upload' => now()->format('Y-m-d'),
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('dokumentasi.index')
                ->with('success', 'Foto dokumentasi berhasil diunggah.');
        }

        return redirect()->route('dokumentasi.index')
            ->with('error', 'Gagal mengunggah file.');
    }

    public function destroy($id)
    {
        $dokumentasi = Dokumentasi::findOrFail($id);

        // Delete from storage
        if ($dokumentasi->file_path && Storage::disk('public')->exists($dokumentasi->file_path)) {
            Storage::disk('public')->delete($dokumentasi->file_path);
        }

        $dokumentasi->delete();

        return redirect()->route('dokumentasi.index')
            ->with('success', 'Dokumentasi berhasil dihapus.');
    }
}
