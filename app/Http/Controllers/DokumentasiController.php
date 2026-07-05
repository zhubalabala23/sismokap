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
            $disk = config('filesystems.default');
            $path = $request->file('file')->store('dokumentasi', $disk);

            $videoPath = null;
            if ($request->hasFile('video')) {
                $videoPath = $request->file('video')->store('dokumentasi', $disk);
            }

            Dokumentasi::create([
                'proyek_id' => $request->proyek_id,
                'file_path' => $path,
                'video_path' => $videoPath,
                'jenis_dokumentasi' => $request->jenis_dokumentasi,
                'tanggal_upload' => $request->tanggal_upload,
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('dokumentasi.index')
                ->with('success', 'Dokumentasi berhasil diunggah.');
        }

        return redirect()->route('dokumentasi.index')
            ->with('error', 'Gagal mengunggah file.');
    }

    public function destroy($id)
    {
        $dokumentasi = Dokumentasi::findOrFail($id);
        $disk = config('filesystems.default');

        // Delete photo from storage
        if ($dokumentasi->file_path && Storage::disk($disk)->exists($dokumentasi->file_path)) {
            Storage::disk($disk)->delete($dokumentasi->file_path);
        }

        // Delete video from storage
        if ($dokumentasi->video_path && Storage::disk($disk)->exists($dokumentasi->video_path)) {
            Storage::disk($disk)->delete($dokumentasi->video_path);
        }

        $dokumentasi->delete();

        return redirect()->route('dokumentasi.index')
            ->with('success', 'Dokumentasi berhasil dihapus.');
    }
}
