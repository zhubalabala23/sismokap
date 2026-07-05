<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Http\Requests\StoreLokasiRequest;
use App\Http\Requests\UpdateLokasiRequest;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Lokasi::with('proyekAssociated');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lokasi', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('kabupaten_kota', 'like', "%{$search}%")
                  ->orWhere('provinsi', 'like', "%{$search}%")
                  ->orWhereHas('proyekAssociated', function ($qp) use ($search) {
                      $qp->where('nama_proyek', 'like', "%{$search}%")
                        ->orWhere('kode_proyek', 'like', "%{$search}%");
                  });
            });
        }

        $lokasis = $query->paginate(10)->withQueryString();
        $proyeks = \App\Models\Proyek::orderBy('nama_proyek')->get();

        return view('lokasi.index', compact('lokasis', 'search', 'proyeks'));
    }

    public function store(StoreLokasiRequest $request)
    {
        $lokasi = Lokasi::create($request->validated());

        if ($request->filled('proyek_id')) {
            $proyek = \App\Models\Proyek::find($request->proyek_id);
            if ($proyek) {
                $proyek->update(['lokasi_id' => $lokasi->id]);
            }
        }

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Data lokasi berhasil ditambahkan.');
    }

    public function update(UpdateLokasiRequest $request, Lokasi $lokasi)
    {
        $oldProyekId = $lokasi->proyek_id;
        $lokasi->update($request->validated());

        if ($oldProyekId != $lokasi->proyek_id && $lokasi->proyek_id) {
            $proyek = \App\Models\Proyek::find($lokasi->proyek_id);
            if ($proyek) {
                $proyek->update(['lokasi_id' => $lokasi->id]);
            }
        } elseif ($lokasi->proyek_id) {
            $proyek = \App\Models\Proyek::find($lokasi->proyek_id);
            if ($proyek && $proyek->lokasi_id != $lokasi->id) {
                $proyek->update(['lokasi_id' => $lokasi->id]);
            }
        }

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Data lokasi berhasil diperbarui.');
    }

    public function destroy(Lokasi $lokasi)
    {
        try {
            $lokasi->delete();
            return redirect()->route('admin.lokasi.index')
                ->with('success', 'Data lokasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.lokasi.index')
                ->with('error', 'Gagal menghapus lokasi karena memiliki keterkaitan data dengan proyek.');
        }
    }
}
