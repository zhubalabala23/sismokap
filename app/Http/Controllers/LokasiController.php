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
        $query = Lokasi::query();

        if ($search) {
            $query->where('nama_lokasi', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
        }

        $lokasis = $query->paginate(10)->withQueryString();

        return view('lokasi.index', compact('lokasis', 'search'));
    }

    public function store(StoreLokasiRequest $request)
    {
        Lokasi::create($request->validated());

        return redirect()->route('admin.lokasi.index')
            ->with('success', 'Data lokasi berhasil ditambahkan.');
    }

    public function update(UpdateLokasiRequest $request, Lokasi $lokasi)
    {
        $lokasi->update($request->validated());

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
